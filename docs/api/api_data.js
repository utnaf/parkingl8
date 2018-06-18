define({ "api": [
  {
    "type": "get",
    "url": "/api/entries/:id",
    "title": "Request a single entry informations",
    "name": "GetEntry",
    "group": "Entries",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Query": [
          {
            "group": "Query",
            "type": "Int",
            "optional": false,
            "field": "id",
            "description": "<p>The Entry id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "200": [
          {
            "group": "200",
            "type": "Entry",
            "optional": false,
            "field": "entry",
            "description": "<p>The requested entry</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success Response",
          "content": "{\n  \"entry\": {\n    \"id\": 578,\n    \"parking_lot_id\": 1,\n    \"arrived_at\": \"2018-06-02 03:05:47\",\n    \"payed_at\": \"2018-06-02 09:57:37\",\n    \"exited_at\": \"2018-06-02 10:03:49\",\n    \"price\": 280.43\n  }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "404": [
          {
            "group": "404",
            "type": "Int",
            "optional": false,
            "field": "status",
            "description": "<p>Status of the request</p>"
          },
          {
            "group": "404",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>String containing the error</p>"
          }
        ]
      }
    },
    "filename": "app/Http/Controllers/EntriesController.php",
    "groupTitle": "Entries"
  },
  {
    "type": "patch",
    "url": "/api/entries/:id/price",
    "title": "Calculate the price for the requested entry",
    "name": "GetEntryPrice",
    "group": "Entries",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Query": [
          {
            "group": "Query",
            "type": "Int",
            "optional": false,
            "field": "id",
            "description": "<p>The entry id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "200": [
          {
            "group": "200",
            "type": "Float",
            "optional": false,
            "field": "price",
            "description": "<p>The entry price</p>"
          }
        ],
        "304": [
          {
            "group": "304",
            "type": "None",
            "optional": false,
            "field": "empty",
            "description": "<p>The response body is empty</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success Response",
          "content": "{\n  \"price\": 22.3\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "404": [
          {
            "group": "404",
            "type": "Int",
            "optional": false,
            "field": "status",
            "description": "<p>Status of the request</p>"
          },
          {
            "group": "404",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>String containing the error</p>"
          }
        ]
      }
    },
    "filename": "app/Http/Controllers/EntriesController.php",
    "groupTitle": "Entries"
  },
  {
    "type": "patch",
    "url": "/api/entries/:id",
    "title": "Update an entry both for payment or for exit",
    "name": "UpdateEntry",
    "group": "Entries",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Query": [
          {
            "group": "Query",
            "type": "Int",
            "optional": false,
            "field": "id",
            "description": "<p>The entry id</p>"
          }
        ],
        "BodyForPayment": [
          {
            "group": "BodyForPayment",
            "type": "Float",
            "optional": false,
            "field": "price",
            "description": "<p>The price that the entry is going to pay</p>"
          }
        ],
        "BodyForExit": [
          {
            "group": "BodyForExit",
            "type": "DateTime",
            "optional": false,
            "field": "exited_at",
            "description": "<p>The time when the entry requested to exit</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "200": [
          {
            "group": "200",
            "type": "Entry",
            "optional": false,
            "field": "entry",
            "description": "<p>The updated entry</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success Response",
          "content": "{\n  \"entry\": {\n    \"id\": 578,\n    \"parking_lot_id\": 1,\n    \"arrived_at\": \"2018-06-02 03:05:47\",\n    \"payed_at\": \"2018-06-02 09:57:37\",\n    \"exited_at\": \"2018-06-02 10:03:49\",\n    \"price\": 280.43\n  }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "400": [
          {
            "group": "400",
            "type": "Int",
            "optional": false,
            "field": "status",
            "description": "<p>Status of the request</p>"
          },
          {
            "group": "400",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>String containing the error</p>"
          }
        ],
        "404": [
          {
            "group": "404",
            "type": "Int",
            "optional": false,
            "field": "status",
            "description": "<p>Status of the request</p>"
          },
          {
            "group": "404",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>String containing the error</p>"
          }
        ],
        "406": [
          {
            "group": "406",
            "type": "Int",
            "optional": false,
            "field": "status",
            "description": "<p>Status of the request</p>"
          },
          {
            "group": "406",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>String containing the error</p>"
          }
        ]
      }
    },
    "filename": "app/Http/Controllers/EntriesController.php",
    "groupTitle": "Entries"
  },
  {
    "type": "post",
    "url": "/api/lots/:id/entries",
    "title": "Create a new entry in the specified lot",
    "name": "AddEntryToParkingLot",
    "group": "ParkingLots",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Query": [
          {
            "group": "Query",
            "type": "Int",
            "optional": false,
            "field": "id",
            "description": "<p>The ParkingLot id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "201": [
          {
            "group": "201",
            "type": "Entry",
            "optional": false,
            "field": "entry",
            "description": "<p>The created entry</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success Response",
          "content": "{\n  \"entry\": {\n    \"id\": 578,\n    \"parking_lot_id\": 1,\n    \"arrived_at\": \"2018-06-02 03:05:47\",\n    \"payed_at\": \"2018-06-02 09:57:37\",\n    \"exited_at\": \"2018-06-02 10:03:49\",\n    \"price\": 280.43\n  }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "404": [
          {
            "group": "404",
            "type": "Int",
            "optional": false,
            "field": "status",
            "description": "<p>Status of the request</p>"
          },
          {
            "group": "404",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>String containing the error</p>"
          }
        ],
        "406": [
          {
            "group": "406",
            "type": "Int",
            "optional": false,
            "field": "status",
            "description": "<p>Status of the request</p>"
          },
          {
            "group": "406",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>String containing the error</p>"
          }
        ]
      }
    },
    "filename": "app/Http/Controllers/ParkingLotController.php",
    "groupTitle": "ParkingLots"
  },
  {
    "type": "get",
    "url": "/api/lots/:id",
    "title": "Request a single parking lot informations",
    "name": "GetParkingLot",
    "group": "ParkingLots",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Query": [
          {
            "group": "Query",
            "type": "Int",
            "optional": false,
            "field": "id",
            "description": "<p>The ParkingLot id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "200": [
          {
            "group": "200",
            "type": "ParkingLot",
            "optional": false,
            "field": "lot",
            "description": "<p>The requested parking lot</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success Response",
          "content": "{\n  \"lot\": {\n    \"id\": 1,\n    \"name\": \"Paucek Inc\",\n    \"hourly_fare\": 0.85,\n    \"capacity\": 626,\n    \"taken_spots\": 46\n  }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "404": [
          {
            "group": "404",
            "type": "Int",
            "optional": false,
            "field": "status",
            "description": "<p>Status of the request</p>"
          },
          {
            "group": "404",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>String containing the error</p>"
          }
        ]
      }
    },
    "filename": "app/Http/Controllers/ParkingLotController.php",
    "groupTitle": "ParkingLots"
  },
  {
    "type": "get",
    "url": "/api/lots/:id/entries",
    "title": "Request all the entries of a lot",
    "name": "GetParkingLotEntries",
    "group": "ParkingLots",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Query": [
          {
            "group": "Query",
            "type": "Int",
            "optional": false,
            "field": "id",
            "description": "<p>The ParkingLot id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "200": [
          {
            "group": "200",
            "type": "Entries[]",
            "optional": false,
            "field": "entries",
            "description": "<p>The list of the entries for a parking log</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success Response",
          "content": "{\n  \"entries\": [\n    {\n      \"id\": 578,\n      \"parking_lot_id\": 1,\n      \"arrived_at\": \"2018-06-02 03:05:47\",\n      \"payed_at\": \"2018-06-02 09:57:37\",\n      \"exited_at\": \"2018-06-02 10:03:49\",\n      \"price\": 280.43\n    }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "404": [
          {
            "group": "404",
            "type": "Int",
            "optional": false,
            "field": "status",
            "description": "<p>Status of the request</p>"
          },
          {
            "group": "404",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>String containing the error</p>"
          }
        ]
      }
    },
    "filename": "app/Http/Controllers/ParkingLotController.php",
    "groupTitle": "ParkingLots"
  },
  {
    "type": "get",
    "url": "/api/lots",
    "title": "Request parking lots list",
    "name": "GetParkingLots",
    "group": "ParkingLots",
    "version": "1.0.0",
    "parameter": {
      "fields": {
        "Query": [
          {
            "group": "Query",
            "type": "Int",
            "optional": false,
            "field": "id",
            "description": "<p>The ParkingLot id</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "200": [
          {
            "group": "200",
            "type": "ParkingLot[]",
            "optional": false,
            "field": "lots",
            "description": "<p>List of parking lots</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success Response",
          "content": "{\n  \"lots\": [\n    {\n      \"id\": 1,\n      \"name\": \"Paucek Inc\",\n      \"hourly_fare\": 0.85,\n      \"capacity\": 626,\n      \"taken_spots\": 46\n    }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "filename": "app/Http/Controllers/ParkingLotController.php",
    "groupTitle": "ParkingLots"
  }
] });
