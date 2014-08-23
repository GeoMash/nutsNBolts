Nuts n Bolts REST API
=====================
##Authentication Service

---

###Authenticate

Handles user authentication.

**URL**

`rest/auth/authenticate`

**Method:**
	
`POST`
	
**URL Params**

None

**Data Params**

	**Required:**
 
	`email=[string]`
 
	`password=[string]`

**Success Response:**
	
* **Code:** 200 <br>
* **Content:** 
```
	{
		success:	true,
		message:	'OK',
		data:		true //Or a string containing a return URL.
	}
```

**Error Response:**

* **Code:** 401 UNAUTHORIZED <br>
* **Content:**
```
	{
		success:	false,
		message:	'Authentication failed',
		data:		null
	}
```

**Sample Call:**

```
	$.post
	(
		'/rest/auth/authenticate.json',
		{
			email:		'user@example.com',
			passsword:	'MySuperAwesomeP@$$W0RD'
		}
	).done
	(
		function(response)
		{
			if (response.success && typeof response.data=='string')
			{
				window.location=response.data;
			}
		}
	);
```

**Notes:**

Remember to run this over https.