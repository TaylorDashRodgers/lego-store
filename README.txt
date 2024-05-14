1. Instead of storing the passwords in plaintext to enhance security, it is common to use cryptographic hash functions.
This stores the hash of the password, which is a one-way function that converts the password into a fixed-length string of characters
2. A good way to deal with forgotten passwords is to implement a secure password reset mechanism that involves some form of
verification process to make sure its the correct person and then allow them to create a new password. A bad way to deal with forgotten
passwords is to just supply the user with the old password without checking for verification.
3. Somethings to consider when implementing Remember me functionality is a token-based approach, and lastly expiration and renewal.
4. Best practices for cookies is to set the secure flag to ensure that cookies are only sent through HTTPS. Another practice is
to employ the SameSite attribute to define when the cookies should be sent.
5. HTTPS is a secure version of HTTP, the protocol used for transferring data between a user's web browser and a website. It ensures
data is encrypted and secure when exchanged.