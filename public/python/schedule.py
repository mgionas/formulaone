import fastf1
import typer

app = typer.Typer()

@app.command()
def getschedule(year):
    session_race = fastf1.get_event_schedule(int(year), include_testing=True)
    json_string = session_race.to_json(orient='records', date_format='iso')

    print(json_string)

@app.command()
def hello():
    print('json_string')

if __name__ == "__main__":
    app()
