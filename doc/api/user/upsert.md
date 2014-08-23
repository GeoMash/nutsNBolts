Nuts n Bolts REST API
=====================
##User Service

---
###Upsert

Updates or inserts a user.

**URL**

`/rest/user/upsert`

**Method:**

`POST`

**URL Params**

None

**Data Params**

	**Required:**
 
	`email=[string]`

	`password=[string]`
	
	`name_first=[string]`
	
	`name_last=[string]`

***Success Response:**
	
* **Code:** 200 <br>
* **Content:** 
```
	{
		success:	true,
		message:	'OK',
		data:		null
	}
```

**Error Response:**

* **Code:** 400 <br>
* **Content:** 
```
	{
		success:	false,
		message:	'Invalid Request',
		data:		null
	}
```

* **Code:** 401 <br>
* **Content:** 
```
	{
		success:	false,
		message:	'Permission Denied',
		data:		null
	}
```

**Sample Call:**

```
	$.post
	(
		'/rest/upsert.json',
		{
			email:		'user@example.com',
			passsword:	'MySuperAwesomeP@$$W0RD',
			name_first:	'Example',
			name_last:	'User'
		}
	).done
	(
		function(response)
		{
			if (!response.success)
			{
				//Deal with it!
			}
		}
	).fail
	(
		function()
		{
			//Deal with it!
		}
	);
```

**Notes:**

None