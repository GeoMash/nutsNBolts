Nuts n Bolts REST API
=====================
##User Service

---
###Impersonate

 Starts impersonation of another user, applying all roles and permissions to the active user.

**URL**

 `/rest/user/impersonate/{userId}/[start|stop]`

**Method:**

 `POST`

**URL Params**

None

**Data Params**

None

**Success Response:**
	
* **Code:** 200
	
* **Content:** 
```
	{
		success:	true,
		message:	'OK',
		data:		null
	}
```

**Error Response:**

* **Code:** 417
	
* **Content:** 
```
	{
		success:	false,
		message:	'You cannot impersonate root!',
		data:		null
	}
```

* **Code:** 417
	
* **Content:** 
```
	{
		success:	false,
		message:	'You cannot impersonate yourself!',
		data:		null
	}
```

* **Code:** 417
	
* **Content:** 
```
	{
		success:	false,
		message:	'Invalid user ID.',
		data:		null
	}
```

* **Code:** 401
	
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