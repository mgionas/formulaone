import fastf1
import typer
import json

app = typer.Typer()



@app.command()
def getSession(year, name, type):
    fastf1.Cache.clear_cache('python/cache')
    fastf1.Cache.disabled()

    session = fastf1.get_session(int(year), str(name), str(type))
    #session = fastf1.get_session(2024, 'FORMULA 1 STC SAUDI ARABIAN GRAND PRIX 2024', 'Race')

    session.load()

    results  = session.results
    info = session.session_info

    json_results = results.to_json(orient='records', date_format='iso')
    json_info = json.dumps(info, default=str)

    combined_data = json.dumps({'results': json.loads(json_results), 'info': json.loads(json_info)})

    print(combined_data)



@app.command()
def getFutureSession(year, name, type):
    fastf1.Cache.clear_cache('python/cache')
    fastf1.Cache.disabled()

    session = fastf1.get_session(int(year), str(name), str(type))

    session.load()

    info = session.session_info

    json_info = json.dumps(info, default=str)

    combined_data = json.dumps({'info': json.loads(json_info)})

    print(combined_data)


if __name__ == "__main__":
    app()
