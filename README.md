## eBay item search using Bootstrap, JQuery, AJAX, and JSON

Creat a webpage that allows users to search items for sale on eBay.com using their API and the results will be displayed in a list format with pagination links below.

After validating the data that user enters, HTML file sends input data using jQuery AJAX call to the server with “GET” http request, and PHP script sends query with arguments to eBay API.
PHP encodes the retrieved XML into JSON and sends back to HTML, and jQuery use the returned data reorganizing HTML contents for results display.

## Programming langueges/Libraries

HTML, CSS, XML, XMLHttpRequest, PHP, Bootstrap, JQuery, AJAX, and JSON

## Implementation details

1.	Use bootstrap form to display the search form in a nicer-looking format.

2.	Check whether the provided data is valid or not based on validation rules using JQuery.

3.	Make an AJAX (asynchronous HTTP) request to PHP script using JavaScript with “GET” method.

4.	Create URL for visiting eBay API with data received from front end. Get and parse XML from eBay API, extract only the parts we need and convert them to JSON.

5.	Display the results with returned JSON.

  1)	Display the item list;
  
  2)	Updated Pagination Bar; 
  
  3)	View detail using Bootstrap collapse;
  
  4)  Item image display using Bootstrap Modal.
  
## Video display

https://www.youtube.com/watch?v=Xgen7RP_lS0
