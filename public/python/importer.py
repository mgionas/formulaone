import asyncio
from functools import partial
import json
import time
import types
import logging
import base64
import zlib
import ast
import typer
from fastf1.utils import to_datetime
from datetime import datetime


from fastf1.livetiming.client import SignalRClient

import mysql.connector
from mysql.connector import Error

log = logging.getLogger(__name__)
log.setLevel(logging.INFO)

token = "LoOFvHw1tUXrUZ8oUqaozmEjxxG9UNO5H5YfRI4cGu306xwQVu_KMNxRYRMrWbhdD886N2PuRgpo9v4v_58pHw=="
org = "f1"
bucket = "data"

# MySQL configuration
mysql_host = "45.79.249.235"
mysql_user = "root"
mysql_password = "Gnusm@sP1e"
mysql_db = "formulaone"

app = typer.Typer()

# mysql

def create_mysql_connection():
    """Create and return a MySQL database connection."""
    try:
        connection = mysql.connector.connect(
            host=mysql_host,
            user=mysql_user,
            passwd=mysql_password,
            database=mysql_db
        )
        log.info("Connection to MySQL DB successful")
        return connection
    except Error as e:
        log.error(f"The error '{e}' occurred")
        return None

def write_to_mysql_live_positions(connection, data):
    """Write data to a MySQL table."""
    # Example query - adjust to match your schema
    query = "INSERT INTO live_positions (raceId, positionTimestamp, pilotPositions) VALUES (%s, %s, %s)"
    try:
        cursor = connection.cursor()
        cursor.execute(query, data)
        connection.commit()
        log.info("Data written to MySQL successfully")
    except Error as e:
        log.error(f"Failed to write to MySQL: {e}")

def write_to_mysql_telemetry(connection, data):
    """Write data to a MySQL table."""
    # Example query - adjust to match your schema
    query = "INSERT INTO telemetry (raceId, telemetryTimestamp, liveTelemetry) VALUES (%s, %s, %s)"
    try:
        cursor = connection.cursor()
        cursor.execute(query, data)
        connection.commit()
        log.info("Data written to MySQL successfully")
    except Error as e:
        log.error(f"Failed to write to MySQL: {e}")


#old
def fix_json(line):
    # fix F1's not json compliant data
    line = line.replace("'", '"') \
        .replace('True', 'true') \
        .replace('False', 'false')
    return line


def _to_file_overwrite(write_api, self, msg):
    mysql_connection = create_mysql_connection()
    if not mysql_connection:
       return 1

    # start here
    arrays = ast.literal_eval(msg)
    print(arrays)
    n = 0
    if arrays[0] == 'Position.z':
        decoded_data = zlib.decompress(base64.b64decode(arrays[1]), -zlib.MAX_WBITS)

        # Decode byte string to string
        response_str = decoded_data.decode('utf-8')
        # Convert string to Python data structure (dictionary)
        data = json.loads(response_str)
        # Assuming you want the list of positions
        positions = data['Position']

        for position in positions:
            
            data2 = position['Entries']
            # print('position Entries -> ', n,' - ', position)

            # Parse the string to a datetime object
            dt_object = datetime.strptime(str(position['Timestamp']), "%Y-%m-%dT%H:%M:%S.%fZ")

            # Assuming your column supports milliseconds (DATETIME(3)), format accordingly
            # Truncate or round the microseconds to milliseconds as necessary
            mysql_datetime_str_with_ms = dt_object.strftime("%Y-%m-%d %H:%M:%S.%f")[:-3]

            print('old -> ', str(position['Timestamp']) ,'converted -> ',mysql_datetime_str_with_ms)
            #write to mysql
            mysql_data = (mysql_datetime_str_with_ms, json.dumps(position['Entries']))  # Example format
            write_to_mysql_live_positions(mysql_connection, mysql_data)
        
            n += 1
   
        


def _start_overwrite(self):
    """Connect to the data stream and start writing the data."""
    try:
        asyncio.run(self._async_start())
    except KeyboardInterrupt:
        self.logger.warning("Keyboard interrupt - exiting...")
        raise KeyboardInterrupt

#store live data to database
def store_live_data():
    """
    Stores live data in a influx database
    This function only works if a f1 live session is active.
    """
    mysql_connection = create_mysql_connection()
    if not mysql_connection:
       return 1

    try:
        while True:
            client = SignalRClient("unused.txt")
            overwrite = partial(_to_file_overwrite, mysql_connection)
            client._to_file = types.MethodType(overwrite,
                                               client)  # Override _to_file methode from fastf1 so the data will be stored in db rather then in a file
            client.start = types.MethodType(_start_overwrite, client)
            client.start()
            
    except KeyboardInterrupt:
        print('interrupted!')

# store mock data to database
def store_mock_data(path_to_saved_data, race_id, speedup_factor=1):
    mysql_connection = create_mysql_connection()
    if not mysql_connection:
       return 1

    # Load old live data from file
    with open(path_to_saved_data) as file:
        arrays = [ast.literal_eval(line.rstrip()) for line in file]

    n = 0
    for line in arrays:

        if line[0] == 'Position.z':
            decoded_data = zlib.decompress(base64.b64decode(line[1]), -zlib.MAX_WBITS)

            # Decode byte string to string
            response_str = decoded_data.decode('utf-8')
            # Convert string to Python data structure (dictionary)
            data = json.loads(response_str)
            # Assuming you want the list of positions
            positions = data['Position']

            for position in positions:
                timestamp_str = position['Timestamp']
                # Trim the string to ensure it matches the '%Y-%m-%dT%H:%M:%S.%fZ' format
                trimmed_timestamp_str = timestamp_str[:23] + 'Z' if len(timestamp_str) > 23 else timestamp_str

                # Parse the string to a datetime object
                dt_object = datetime.strptime(trimmed_timestamp_str, "%Y-%m-%dT%H:%M:%S.%fZ")

                # Format accordingly, truncating microseconds to fit MySQL DATETIME(3) precision if needed
                mysql_datetime_str_with_ms = dt_object.strftime("%Y-%m-%d %H:%M:%S.%f")[:-3]

                print('position Entries -> ', n, ' - ', position)
                print('old -> ', timestamp_str, 'converted -> ', mysql_datetime_str_with_ms)
                print('raceID ->', race_id)

                # Example format for MySQL data
                mysql_data = (str(race_id), mysql_datetime_str_with_ms, json.dumps(position['Entries']))  # Example format
                write_to_mysql_live_positions(mysql_connection, mysql_data)

                n += 1

        elif line[0] == 'CarData.z':
            decoded_data = zlib.decompress(base64.b64decode(line[1]), -zlib.MAX_WBITS)

            # Decode byte string to string
            response_str = decoded_data.decode('utf-8')
            # Convert string to Python data structure (dictionary)
            data = json.loads(response_str)
            # Assuming you want the list of positions
            
            entries = data['Entries']

            for entry in entries:
                #print('>>>>>>>>>>',entry['Utc'])
                timestamp_str = entry['Utc']
                # Trim the string to ensure it matches the '%Y-%m-%dT%H:%M:%S.%fZ' format
                trimmed_timestamp_str = timestamp_str[:23] + 'Z' if len(timestamp_str) > 23 else timestamp_str

                # Parse the string to a datetime object
                dt_object = datetime.strptime(trimmed_timestamp_str, "%Y-%m-%dT%H:%M:%S.%fZ")

                # Format accordingly, truncating microseconds to fit MySQL DATETIME(3) precision if needed
                mysql_datetime_str_with_ms = dt_object.strftime("%Y-%m-%d %H:%M:%S.%f")[:-3]

                print('position Entries -> ', n, ' - ', entry)
                print('old -> ', timestamp_str, 'converted -> ', mysql_datetime_str_with_ms)
                print('raceID ->', race_id)

                # Example format for MySQL data
                mysql_data = (str(race_id), mysql_datetime_str_with_ms, json.dumps(entry['Cars']))  # Example format
                write_to_mysql_telemetry(mysql_connection, mysql_data)

                n += 1





# we have two commands Live and Mock data
@app.command()
def process_live_session(influx_url="http://localhost:8086"):
    store_live_data()
    return 0


@app.command()
def process_mock_data(path_to_saved_data, race_id, speedup_factor: int = 1000):
    store_mock_data(path_to_saved_data, race_id, speedup_factor)
    return 0


def main():
    app()


if __name__ == '__main__':
    main()
