###### host: `http://david-php-demo.herokuapp.com/api`

# Member

## 查詢會員清單

### Resource
GET /member/find_member_list

### Query Parameters
| Name     | Type                  |Description                                                                          |
| -------- | --------------------- |:------------------------------------------------------------------------------------ |
| search_key     | string | 關鍵字(option) |

### Request Example
GET /member/find_member_list?search_key=Eula

### Response
```json
{
    "message": [],
    "data": [
        
            {
                "memberSid": "10",
                "name": "Eula Wheeler",
                "cashBalance": "110.18",
                "lastTransactionDate": "2021-01-11 04:29",
                "lastPharmacyName": "Drug Blend"
            }
        
    ]
}
```

## 新增會員資料

### Resource
POST /member/member_data

### Request Body Parameters
| Name     | Type                  |Description                                                                          |
| -------- | --------------------- |:------------------------------------------------------------------------------------ |
| member_name     | String | 姓名 |


### Request Body Parameters Example

```json

{
    "member_name": "Fred"
}

```

### Response

Status-Code: 400 Bad Request

```json
{
    "message": [
        "member_name請勿空白"
    ],
    "data": null
}
```

Status-Code: 200 Http ok

```json

{
    "message": [
        "新增成功"
    ],
    "data": null
}

```

## 修改會員資料

### Resource
PATCH /member/member_data

### Request Body Parameters
| Name     | Type                  |Description                                                                          |
| -------- | --------------------- |:------------------------------------------------------------------------------------ |
| member_sid     | String | 會員sid(memberSid) |
| member_name     | String | 想要修改的名稱 |


### Request Body Parameters Example

```json

{   
    "member_sid":1,
    "member_name": "Keven"
}

```

### Response

Status-Code: 400 Bad Request

```json
{
    "message": [
        "查無會員資料"
    ],
    "data": null
}
```

Status-Code: 200 Http ok

```json

{
    "message": [
        "修改成功"
    ],
    "data": null
}

```

# Common

## 新增顏色標籤

### Resource
POST /common/color_tag

### Request Body Parameters
| Name     | Type                  |Description                                                                          |
| -------- | --------------------- |:------------------------------------------------------------------------------------ |
| tag_name     | String | 標籤名稱 |


### Request Body Parameters Example

```json

{
    "tag_name": "yellow"
}

```

### Response

Status-Code: 400 Bad Request

```json
{
    "message": [
        "tag_name請勿空白"
    ],
    "data": null
}
```

Status-Code: 200 Http ok

```json

{
    "message": [
        "新增成功"
    ],
    "data": null
}

```

## 修改顏色標籤

### Resource
PATCH /common/color_tag

### Request Body Parameters
| Name     | Type                  |Description                                                                          |
| -------- | --------------------- |:------------------------------------------------------------------------------------ |
| tag_name     | String | 舊標籤名稱 |
| modify_tag_name     | String | 想要修改的標籤名稱 |


### Request Body Parameters Example

```json

{
    "tag_name": "yellow",
    "modify_tag_name":"yellow light"
}

```

### Response

Status-Code: 400 Bad Request

```json
{
    "message": [
        "tag_name請勿空白"
    ],
    "data": null
}
```

Status-Code: 200 Http ok

```json

{
    "message": [
        "修改成功"
    ],
    "data": null
}

```

## 新增產品規格標籤

### Resource
POST /common/product_style_tag

### Request Body Parameters
| Name     | Type                  |Description                                                                          |
| -------- | --------------------- |:------------------------------------------------------------------------------------ |
| tag_name     | String | 標籤名稱 |


### Request Body Parameters Example

```json

{
    "tag_name": "8 per pack"
}

```

### Response

Status-Code: 400 Bad Request

```json
{
    "message": [
        "tag_name請勿空白"
    ],
    "data": null
}
```

Status-Code: 200 Http ok

```json

{
    "message": [
        "新增成功"
    ],
    "data": null
}

```

## 修改產品規格標籤

### Resource
PATCH /common/product_style_tag

### Request Body Parameters
| Name     | Type                  |Description                                                                          |
| -------- | --------------------- |:------------------------------------------------------------------------------------ |
| tag_name     | String | 舊標籤名稱 |
| modify_tag_name     | String | 想要修改的標籤名稱 |


### Request Body Parameters Example

```json

{
    "tag_name": "8 per pack",
    "modify_tag_name":"12 per pack"
}

```

### Response

Status-Code: 400 Bad Request

```json
{
    "message": [
        "tag_name請勿空白"
    ],
    "data": null
}
```

Status-Code: 200 Http ok

```json

{
    "message": [
        "修改成功"
    ],
    "data": null
}

```

# Store

## 找指定日期時間營業店家

### Resource
GET /store/search_by_date

### Query Parameters
| Name     | Type                  |Description                                                                          |
| -------- | --------------------- |:------------------------------------------------------------------------------------ |
| date     | string | 日期(ex:2021-04-07) |
| time | string | 時間(ex:18:16)<br>(optional) 

### Request Example
GET /store/search_by_date?date=2021-04-07&time=12:00

### Response
```json
{
    "message": [],
    "data": [
        
            {
                "storeSid": "7",
                "pharmacyName": "Longhorn Pharmacy",
                "openingHours": "Mon, Wed 10:53 - 16:49 / Tue 17:41 - 21:42 / Thu 08:25 - 00:30 / Sun 15:53 - 02:05"
            },
            {
                "storeSid": "8",
                "pharmacyName": "PharmaMed",
                "openingHours": "Mon, Sat 03:27 - 09:16 / Tue 14:41 - 19:40 / Wed 04:05 - 16:06 / Thu 09:49 - 17:25 / Fri 08:05 - 19:48"
            },
            {
                "storeSid": "13",
                "pharmacyName": "Atlas Drugs",
                "openingHours": "Mon 16:30 - 23:07 / Wed 10:16 - 16:48 / Fri 16:20 - 20:39 / Sat 00:17 - 12:55 / Sun 05:33 - 23:59"
            },
            {
                "storeSid": "15",
                "pharmacyName": "Apotheco",
                "openingHours": "Mon, Sat 10:06 - 14:26 / Tue 08:27 - 22:13 / Wed 08:06 - 16:22 / Thu 12:24 - 19:49 / Sun 15:53 - 05:32"
            },
            {
                "storeSid": "16",
                "pharmacyName": "Drug Blend",
                "openingHours": "Mon 04:08 - 20:52 / Tue 01:01 - 06:01 / Wed, Sat 11:18 - 20:37 / Thu 16:44 - 23:43 / Sun 04:26 - 14:48"
            },
            {
                "storeSid": "20",
                "pharmacyName": "DFW Wellness",
                "openingHours": "Mon - Tue 00:20 - 16:06 / Sun, Wed 10:02 - 13:23 / Thu 10:02 - 09:41 / Fri 16:08 - 21:01"
            }
        ]
    
}
```

## 找指定星期營業店家

### Resource
GET /store/search_by_week

### Query Parameters
| Name     | Type                  |Description                                                                          |
| -------- | --------------------- |:------------------------------------------------------------------------------------ |
| week_name     | string</br>(**enum**)  | 星期簡寫(optional)<br> **ALLOWED VALUES**: <br><ul><li>Sun</li><li>Mon</li><li>Tue</li><li>Wed</li><li>Thu</li><li>Fri</li><li>Sat</li> |


### Request Example
GET /store/search_by_week?week_name=Sun

### Response
Status-Code: 400 Bad Request

```json
{
    "message": [
        "日期格式錯誤"
    ],
    "data": null
}
```
Status-Code: 200 Http ok

```json

{
    "message": [],
    "data": [
        
            {
                "storeSid": "1",
                "pharmacyName": "Better You",
                "openingHours": "Mon, Wed 12:56 - 21:58 / Tue 13:06 - 22:42 / Fri - Sat 17:09 - 20:20 / Sun 07:10 - 09:33"
            },
            {
                "storeSid": "2",
                "pharmacyName": "Cash Saver Pharmacy",
                "openingHours": "Mon 11:00 - 14:48 / Tue, Fri 00:05 - 07:20 / Sun, Sat 09:01 - 12:43"
            },
            {
                "storeSid": "3",
                "pharmacyName": "PrecisionMed",
                "openingHours": "Tue 14:10 - 16:25 / Wed 16:57 - 21:46 / Thu 16:30 - 19:40 / Fri 02:55 - 16:49 / Sun 10:59 - 05:33"
            },
            {
                "storeSid": "4",
                "pharmacyName": "MedSavvy",
                "openingHours": "Tue 10:08 - 23:13 / Wed 12:38 - 21:48 / Thu 12:14 - 22:48 / Fri - Sat 15:01 - 21:24 / Sun 00:03 - 07:58"
            },
            {
                "storeSid": "5",
                "pharmacyName": "Pill Pack",
                "openingHours": "Mon 07:14 - 17:06 / Tue 16:47 - 19:25 / Wed 15:30 - 19:00 / Sat 04:35 - 06:35 / Sun 01:39 - 16:59"
            },
            {
                "storeSid": "6",
                "pharmacyName": "Heartland Pharmacy",
                "openingHours": "Mon 13:18 - 17:49 / Tue 05:06 - 17:45 / Wed - Thu 03:25 - 11:25 / Sat 04:10 - 08:03 / Sun 15:07 - 18:50"
            },
            {
                "storeSid": "7",
                "pharmacyName": "Longhorn Pharmacy",
                "openingHours": "Mon, Wed 10:53 - 16:49 / Tue 17:41 - 21:42 / Thu 08:25 - 00:30 / Sun 15:53 - 02:05"
            },
            {
                "storeSid": "9",
                "pharmacyName": "Neighbors",
                "openingHours": "Mon 10:09 - 02:26 / Wed 15:26 - 17:33 / Thu 15:31 - 17:46 / Sat 13:14 - 20:24 / Sun 00:02 - 16:40"
            },
            {
                "storeSid": "10",
                "pharmacyName": "Discount Drugs",
                "openingHours": "Wed 05:16 - 09:37 / Thu 14:04 - 23:19 / Fri - Sat 00:27 - 04:08 / Sun 03:04 - 06:25"
            },
            {
                "storeSid": "11",
                "pharmacyName": "Medlife",
                "openingHours": "Wed 16:49 - 20:32 / Thu 15:57 - 09:13 / Fri - Sat 13:36 - 20:51 / Sun 02:42 - 19:44"
            },
            {
                "storeSid": "12",
                "pharmacyName": "Pride Pharmacy",
                "openingHours": "Mon 07:50 - 14:53 / Thu - Fri 00:53 - 07:57 / Sat 12:20 - 17:45 / Sun 15:50 - 10:49"
            },
            {
                "storeSid": "13",
                "pharmacyName": "Atlas Drugs",
                "openingHours": "Mon 16:30 - 23:07 / Wed 10:16 - 16:48 / Fri 16:20 - 20:39 / Sat 00:17 - 12:55 / Sun 05:33 - 23:59"
            },
            {
                "storeSid": "15",
                "pharmacyName": "Apotheco",
                "openingHours": "Mon, Sat 10:06 - 14:26 / Tue 08:27 - 22:13 / Wed 08:06 - 16:22 / Thu 12:24 - 19:49 / Sun 15:53 - 05:32"
            },
            {
                "storeSid": "16",
                "pharmacyName": "Drug Blend",
                "openingHours": "Mon 04:08 - 20:52 / Tue 01:01 - 06:01 / Wed, Sat 11:18 - 20:37 / Thu 16:44 - 23:43 / Sun 04:26 - 14:48"
            },
            {
                "storeSid": "19",
                "pharmacyName": "RxToMe",
                "openingHours": "Mon 07:12 - 11:46 / Wed 16:24 - 20:15 / Thu 08:59 - 14:07 / Sun, Sat 10:47 - 12:50"
            },
            {
                "storeSid": "20",
                "pharmacyName": "DFW Wellness",
                "openingHours": "Mon - Tue 00:20 - 16:06 / Sun, Wed 10:02 - 13:23 / Thu 10:02 - 09:41 / Fri 16:08 - 21:01"
            }
        
    ]
}

```

## 以販賣商品金額篩選店家

### Resource
GET /store/filter_by_price

### Query Parameters
| Name     | Type                  |Description                                                                          |
| -------- | --------------------- |:------------------------------------------------------------------------------------ |
| min_prcie     | double | 最小金額 |
| max_prcie     | double | 最大金額 |


### Request Example
GET /store/filter_by_price?min_prcie=1.0&max_prcie=6.0

### Response
Status-Code: 400 Bad Request

```json
{
    "message": [
        "min_prcie非數字，請檢查資料格式",
        "max_prcie非數字，請檢查資料格式"
    ],
    "data": null
}
```
Status-Code: 200 Http ok

```json

{
    "message": [],
    "data": [
        
            {
                "storeSid": "4",
                "pharmacyName": "MedSavvy",
                "openingHours": "Tue 10:08 - 23:13 / Wed 12:38 - 21:48 / Thu 12:14 - 22:48 / Fri - Sat 15:01 - 21:24 / Sun 00:03 - 07:58"
            },
            {
                "storeSid": "5",
                "pharmacyName": "Pill Pack",
                "openingHours": "Mon 07:14 - 17:06 / Tue 16:47 - 19:25 / Wed 15:30 - 19:00 / Sat 04:35 - 06:35 / Sun 01:39 - 16:59"
            },
            {
                "storeSid": "6",
                "pharmacyName": "Heartland Pharmacy",
                "openingHours": "Mon 13:18 - 17:49 / Tue 05:06 - 17:45 / Wed - Thu 03:25 - 11:25 / Sat 04:10 - 08:03 / Sun 15:07 - 18:50"
            },
            {
                "storeSid": "11",
                "pharmacyName": "Medlife",
                "openingHours": "Wed 16:49 - 20:32 / Thu 15:57 - 09:13 / Fri - Sat 13:36 - 20:51 / Sun 02:42 - 19:44"
            },
            {
                "storeSid": "12",
                "pharmacyName": "Pride Pharmacy",
                "openingHours": "Mon 07:50 - 14:53 / Thu - Fri 00:53 - 07:57 / Sat 12:20 - 17:45 / Sun 15:50 - 10:49"
            },
            {
                "storeSid": "14",
                "pharmacyName": "Thrifty Way Pharmacy",
                "openingHours": "Mon, Fri 04:02 - 15:08 / Tue 09:57 - 18:23 / Wed 12:10 - 00:10 / Sat 12:21 - 21:32"
            },
            {
                "storeSid": "16",
                "pharmacyName": "Drug Blend",
                "openingHours": "Mon 04:08 - 20:52 / Tue 01:01 - 06:01 / Wed, Sat 11:18 - 20:37 / Thu 16:44 - 23:43 / Sun 04:26 - 14:48"
            }
        ]
    
}

```
## 關鍵字查詢店家

### Resource
GET /store/search_by_name

### Query Parameters
| Name     | Type                  |Description                                                                          |
| -------- | --------------------- |:------------------------------------------------------------------------------------ |
| name     | String | 關鍵字(option) |


### Request Example
GET /store/search_by_name?name=rx

### Response

Status-Code: 200 Http ok

```json

{
    "message": [],
    "data": [
        
            {
                "storeSid": "19",
                "pharmacyName": "RxToMe",
                "openingHours": "Mon 07:12 - 11:46 / Wed 16:24 - 20:15 / Thu 08:59 - 14:07 / Sun, Sat 10:47 - 12:50"
            },
            {
                "storeSid": "18",
                "pharmacyName": "Assured Rx",
                "openingHours": "Mon, Sat 02:30 - 06:43 / Tue - Wed 08:44 - 11:28 / Thu 02:06 - 05:27 / Fri 05:24 - 16:59"
            }
        
    ]
}

```

# Product

## 找店家販賣商品

### Resource
GET /product/find_product

### Query Parameters
| Name     | Type                  |Description                                                                          |
| -------- | --------------------- |:------------------------------------------------------------------------------------ |
| store_sid     | int | 店家sid(storeSid) |


### Request Example
GET /product/find_product?store_sid=2

### Response

Status-Code: 400 Bad Request

```json

{
    "message": [
        "查無店家，請確認送出參數"
    ],
    "data": null
}

```

Status-Code: 200 Http ok

```json

{
    "message": [],
    "data": [
        
            {
                "productAttrSid": "3",
                "storeSid": "2",
                "pharmacyName": "Cash Saver Pharmacy",
                "maskName": "Free to Roam (black) (3 per pack)",
                "price": "13.83"
            },
            {
                "productAttrSid": "2",
                "storeSid": "2",
                "pharmacyName": "Cash Saver Pharmacy",
                "maskName": "MaskT (black) (10 per pack)",
                "price": "14.90"
            },
            {
                "productAttrSid": "5",
                "storeSid": "2",
                "pharmacyName": "Cash Saver Pharmacy",
                "maskName": "Masquerade (blue) (6 per pack)",
                "price": "16.75"
            },
            {
                "productAttrSid": "4",
                "storeSid": "2",
                "pharmacyName": "Cash Saver Pharmacy",
                "maskName": "AniMask (green) (10 per pack)",
                "price": "49.21"
            }
        
    ]
}
```

## 以金額篩選商品

### Resource
GET /product/filter_product_by_pric

### Query Parameters
| Name     | Type                  |Description                                                                          |
| -------- | --------------------- |:------------------------------------------------------------------------------------ |
| min_prcie     | double | 最小金額 |
| max_prcie     | double | 最大金額 |


### Request Example
GET /product/filter_product_by_price?min_prcie=0.1&max_prcie=4.0

### Response

Status-Code: 400 Bad Request

```json

{
    "message": [
        "min_prcie非數字，請檢查資料格式",
        "max_prcie非數字，請檢查資料格式"
    ],
    "data": null
}

```

Status-Code: 200 Http ok

```json

{
    "message": [],
    "data": [
        
            {
                "productAttrSid": "61",
                "storeSid": "14",
                "pharmacyName": "Thrifty Way Pharmacy",
                "maskName": "AniMask (green) (3 per pack)",
                "price": "3.23"
            },
            {
                "productAttrSid": "17",
                "storeSid": "5",
                "pharmacyName": "Pill Pack",
                "maskName": "Masquerade (black) (3 per pack)",
                "price": "3.76"
            }
        
    ]
}

```

## 關鍵字查詢商品

### Resource
GET /product/search_by_name

### Query Parameters
| Name     | Type                  |Description                                                                          |
| -------- | --------------------- |:------------------------------------------------------------------------------------ |
| name     | String | 關鍵字(option) |


### Request Example
GET /product/search_by_name?name=10

### Response
Status-Code: 200 Http ok

```json

{
    "message": [],
    "data": [
        
            {
                "productAttrSid": "2",
                "maskName": "MaskT (black) (10 per pack)",
                "storeSid": "2",
                "pharmacyName": "Cash Saver Pharmacy",
                "price": "14.90"
            },
            {
                "productAttrSid": "19",
                "maskName": "MaskT (green) (10 per pack)",
                "storeSid": "5",
                "pharmacyName": "Pill Pack",
                "price": "32.57"
            },
            {
                "productAttrSid": "25",
                "maskName": "MaskT (green) (10 per pack)",
                "storeSid": "6",
                "pharmacyName": "Heartland Pharmacy",
                "price": "35.06"
            },
            {
                "productAttrSid": "35",
                "maskName": "MaskT (green) (10 per pack)",
                "storeSid": "9",
                "pharmacyName": "Neighbors",
                "price": "47.83"
            },
            {
                "productAttrSid": "44",
                "maskName": "MaskT (black) (10 per pack)",
                "storeSid": "11",
                "pharmacyName": "Medlife",
                "price": "43.94"
            },
            {
                "productAttrSid": "47",
                "maskName": "MaskT (black) (10 per pack)",
                "storeSid": "12",
                "pharmacyName": "Pride Pharmacy",
                "price": "46.51"
            },
            {
                "productAttrSid": "72",
                "maskName": "MaskT (black) (10 per pack)",
                "storeSid": "18",
                "pharmacyName": "Assured Rx",
                "price": "46.69"
            },
            {
                "productAttrSid": "77",
                "maskName": "MaskT (green) (10 per pack)",
                "storeSid": "18",
                "pharmacyName": "Assured Rx",
                "price": "39.40"
            },
            {
                "productAttrSid": "50",
                "maskName": "AniMask (blue) (10 per pack)",
                "storeSid": "12",
                "pharmacyName": "Pride Pharmacy",
                "price": "11.47"
            },
            {
                "productAttrSid": "55",
                "maskName": "AniMask (blue) (10 per pack)",
                "storeSid": "14",
                "pharmacyName": "Thrifty Way Pharmacy",
                "price": "34.39"
            },
            {
                "productAttrSid": "4",
                "maskName": "AniMask (green) (10 per pack)",
                "storeSid": "2",
                "pharmacyName": "Cash Saver Pharmacy",
                "price": "49.21"
            },
            {
                "productAttrSid": "20",
                "maskName": "AniMask (green) (10 per pack)",
                "storeSid": "5",
                "pharmacyName": "Pill Pack",
                "price": "22.01"
            },
            {
                "productAttrSid": "27",
                "maskName": "AniMask (green) (10 per pack)",
                "storeSid": "7",
                "pharmacyName": "Longhorn Pharmacy",
                "price": "10.83"
            },
            {
                "productAttrSid": "54",
                "maskName": "AniMask (green) (10 per pack)",
                "storeSid": "14",
                "pharmacyName": "Thrifty Way Pharmacy",
                "price": "25.42"
            },
            {
                "productAttrSid": "59",
                "maskName": "AniMask (black) (10 per pack)",
                "storeSid": "14",
                "pharmacyName": "Thrifty Way Pharmacy",
                "price": "36.31"
            },
            {
                "productAttrSid": "30",
                "maskName": "Masquerade (blue) (10 per pack)",
                "storeSid": "7",
                "pharmacyName": "Longhorn Pharmacy",
                "price": "21.67"
            },
            {
                "productAttrSid": "63",
                "maskName": "Masquerade (blue) (10 per pack)",
                "storeSid": "15",
                "pharmacyName": "Apotheco",
                "price": "38.33"
            },
            {
                "productAttrSid": "11",
                "maskName": "Masquerade (black) (10 per pack)",
                "storeSid": "4",
                "pharmacyName": "MedSavvy",
                "price": "19.54"
            },
            {
                "productAttrSid": "21",
                "maskName": "Masquerade (green) (10 per pack)",
                "storeSid": "5",
                "pharmacyName": "Pill Pack",
                "price": "42.27"
            },
            {
                "productAttrSid": "6",
                "maskName": "Second Smile (blue) (10 per pack)",
                "storeSid": "3",
                "pharmacyName": "PrecisionMed",
                "price": "39.98"
            },
            {
                "productAttrSid": "12",
                "maskName": "Free to Roam (blue) (10 per pack)",
                "storeSid": "4",
                "pharmacyName": "MedSavvy",
                "price": "30.74"
            },
            {
                "productAttrSid": "36",
                "maskName": "Free to Roam (blue) (10 per pack)",
                "storeSid": "10",
                "pharmacyName": "Discount Drugs",
                "price": "38.41"
            },
            {
                "productAttrSid": "73",
                "maskName": "Free to Roam (blue) (10 per pack)",
                "storeSid": "18",
                "pharmacyName": "Assured Rx",
                "price": "15.79"
            },
            {
                "productAttrSid": "13",
                "maskName": "Free to Roam (black) (10 per pack)",
                "storeSid": "4",
                "pharmacyName": "MedSavvy",
                "price": "26.54"
            },
            {
                "productAttrSid": "75",
                "maskName": "Free to Roam (black) (10 per pack)",
                "storeSid": "18",
                "pharmacyName": "Assured Rx",
                "price": "35.66"
            }
        
    ]
}

```

## 更改商品資料

### Resource
PATCH /product/product_data

### Request Body Parameters
| Name     | Type                  |Description                                                                          |
| -------- | --------------------- |:------------------------------------------------------------------------------------ |
| produat_attr_sid     | int | 商品sid(produatAttrSid) |
| produat_name     | String | 商品名稱 |
| color_tag     | String | 顏色標籤 |
| type_tag     | String | 規格標籤 |
| price     | double | 商品金額 |

### Request Body Parameters Example

```json

{
    "produat_attr_sid": 2,
    "produat_name": "change mask",
    "color_tag": "blue",
    "type_tag": "6 per pack",
    "price":2.6
}

```

### Response

Status-Code: 400 Bad Request

```json

{
    "message": [
        "查無商品資料",
        "商品名稱請勿空白",
        "查無顏色資料",
        "查無規格資料",
        "price請輸入大於0的浮點數"
    ],
    "data": null
}

```

Status-Code: 200 Http ok

```json

{
    "message": [
        "修改成功"
    ],
    "data": null
}

```

## 刪除商品資料

### Resource
DELETE /product/product_data

### Request Body Parameters
| Name     | Type                  |Description                                                                          |
| -------- | --------------------- |:------------------------------------------------------------------------------------ |
| produat_attr_sid     | int | 商品sid(produatAttrSid) |

### Request Body Parameters Example

```json

{
    "produat_attr_sid": 2
}

```

### Response

Status-Code: 400 Bad Request

```json

{
    "message": [
        "查無商品資料"
    ],
    "data": null
}

```

Status-Code: 200 Http ok

```json

{
    "message": [
        "刪除成功"
    ],
    "data": null
}

```

# Transaction

## 搜尋會員交易排行

### Resource
GET /transaction/find_transaction_top

### Query Parameters
| Name     | Type                  |Description                                                                          |
| -------- | --------------------- |:------------------------------------------------------------------------------------ |
| start_time     | String | 開始時間(ex:1609430400) |
| end_time     | String | 結束時間(ex:1609862399) |
| limit_num     | int | 找前X名 |


### Request Example
GET /transaction/find_transaction_top?start_time=1609430400&end_time=1609862399&limit_num=3

#### Response

Status-Code: 400 Bad Request

```json

{
    "message": [
        "limit_num請輸入大於0的整數"
    ],
    "data": null
}
```

Status-Code: 200 Http ok

```json

{
    "message": [],
    "data": [
        
            {
                "memberSid": "12",
                "memberName": "Tamara Dean",
                "transactionAmount": "60.88"
            },
            {
                "memberSid": "6",
                "memberName": "Jo Barton",
                "transactionAmount": "60.14"
            },
            {
                "memberSid": "20",
                "memberName": "Bobbie Russell",
                "transactionAmount": "42.29"
            }
        
    ]
}

```

## 取得區間內交易總額

### Resource
GET /transaction/get_total

### Query Parameters
| Name     | Type                  |Description                                                                          |
| -------- | --------------------- |:------------------------------------------------------------------------------------ |
| start_time     | String | 開始時間(ex:1609430400) |
| end_time     | String | 結束時間(ex:1609862399) |


### Request Example
GET transaction/get_total?start_time=1609430400&end_time=1609862399

### Response

Status-Code: 400 Bad Request

```json

{
    "message": [
        "請輸入開始時間"
    ],
    "data": null
}
```

Status-Code: 200 Http ok

```json

{
    "message": [],
    "data": [
        
            {
                "memberSid": "12",
                "memberName": "Tamara Dean",
                "transactionAmount": "60.88"
            },
            {
                "memberSid": "6",
                "memberName": "Jo Barton",
                "transactionAmount": "60.14"
            },
            {
                "memberSid": "20",
                "memberName": "Bobbie Russell",
                "transactionAmount": "42.29"
            }
        
    ]
}

```

## 新增交易

### Resource
POST /transaction/order

### Request Body Parameters
| Name     | Type                  |Description                                                                          |
| -------- | --------------------- |:------------------------------------------------------------------------------------ |
| produat_attr_sid     | int | 商品sid(produatAttrSid) |
| member_sid     | int | 會員sid(memberSid) |

### Request Body Parameters Example

```json

{
    "produat_attr_sid": 2,
    "member_sid": 1
}

```

### Response

Status-Code: 400 Bad Request

```json

{
    "message": [
        "查無商品資料",
        "查無會員資料"
    ],
    "data": null
}

```

Status-Code: 200 Http ok

```json

{
    "message": [
        "交易成功"
    ],
    "data": null
}

```
