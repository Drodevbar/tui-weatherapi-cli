## Table of contents
1. [Step #1 (development)](#development)
2. [Step #2 (API design)](#api-design)

## Development

Requirements:
* `Docker` && `docker-compose`

Running the project:
* _ensure Docker Daemon is running_
* `cp .env.example .env`
* _fill out all environmental variables inside `.env`_
* `docker-compose up`

Brief information:
* project uses PHP 8.0 so expect some new features inside _(yay!)_
* code is divided into 2 separate directories: `src` and `tests` - check them out!

## Api design

### endpoint/s to set the forecast for a specific city

`PATCH /api/v3/cities/{id}`

Payload (`JSON`):
```json
{
  "weather": [
    {
      "date": "2020-12-24",
      "conditionText": "foo"
    },
    {
      "date": "2020-12-25",
      "conditionText": "bar"
    },
    // ...
  ]
}
```

Possible responses:

| Status  | Description                                                                                                                                       |
|---------|---------------------------------------------------------------------------------------------------------------------------------------------------|
| 200/204 | City updated successfully; <br/> 200 if we would like to return city as response body; <br/>  204 if no content to be returned                    |
| 404     | City not found                                                                                                                                    |
| 400     | If there was some malfunction with json format under `weather` key; <br/> In response body we could include what went wrong during validation     |

### endpoint/s to read the forecast for a specific city

`GET /api/v3/cities/{id}/weather?day=<Y-m-d>`

1. If no `day` query param passed - return weather for today
2. If `day` query param passed - return weather for specific day
3. If `day` query param was passed in the wrong format, I would default to today (but as well some error message and proper status code might be returned)

Possible responses:

| Status | Description                       |
|--------|-----------------------------------|
| 200    | City found. Weather data returned |
| 404    | City not found                    |
