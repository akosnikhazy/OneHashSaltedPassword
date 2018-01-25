# OneHashSaltedPassword
This class is for generate and check password hashes that actually contains the salt too.

The idea:

This way in the database you can store a single hash value in the password column while
it is salted properly so even at the same password you will see different hash values.

Also if an attacker steals only your user database they will assume you store your password
in plain sha256, not knowing it is actually a combination of a password and a salt, making 
their work harder.
