# Customer Account Product Search

This module was developed with the intent of creating a custom Product Search inside the
customer account section.

To access this section go to: customer/account/productsbyrange. The user must be logged in
to access the section.

This section consists of a form with three fields, the 'Low Range'
field, the 'High Range' field and the 'Sort Order' field, a submit button and a table with
the following columns: Thumbnail, SKU, Name, Quantity, Price and Product URL.


The form will not submit if the following conditions are not met:
- Low range value is 0 or bigger.
- High range value is bigger than 0.
- Low range value is lower than High range value.
- High range value is not more 5 times higher than the lower value.






## Installation

The installation is simple, just paste the contents of this file inside app/code.

## Tests

Unit tests have been developed for the Models. They can be found at
CrimsonAgility/CustomerSortProducts/Test/Unit.

## Javascript and Ajax Requests

The javascript written in this module was mainly to create validators and to access the
created endpoint for the ajax calls. To perform the ajax call, the mage/storage package was used.
In case there are any other doubts, feel free to contact me.
## Authors

- Tales Duque: [talesfduque@gmail.com](mailto:talesfduque@gmail.com)
- Whatsapp: +5532988117083
