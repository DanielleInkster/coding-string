## Challenge

A string parameter is passed to the function – attached are various samples of possible input data
The function receives this data as a string, *not an array, JSON or other data formats*
The function should parse the string and mask sensitive data.
Sensitive data should be masked (replaced) with an Asterix (*) character.
Number of (*) should match the original number of characters in that sensitive data.

Sensitive data includes the fields below, but new sensitive fields should be easily added to the function as needed:
- The credit card number
- The credit card expiry date
- The credit card CVV value

The function returns the parsed string in the same format that it was provided, but with the sensitive data now masked.
