<h1>Requirements</h1>
<p>PHP 7.2.34 or higher.</p>

<h1>Installation</h1>
<p>Clone the repository to a folder v_thru.</p>
<p>Use composer to install Symfony and packages using the composer.json file.</p>

<h1>Description</h1>
<p>Endpoint testing was done in Postman</>

<h3>Create a new Product</h3>
<p>POST /product</p>
<p>request JSON:<br>
{<br>
    "name" : "string",<br>
    "price" : number<br>
}<br></p>

<h3>Create a new Order</h3>
<p>POST /order</p>
<p>request JSON:<br>
{<br>
}<br></p>

<h3>Add a Product to an Order</h3>
<p>POST /order/product/add</p>
<p>request JSON:<br>
{<br>
    "id":number,<br>
    "products":[<br>
        {"id":number, "quantity":number},<br>
    ]<br>
}<br></p>

<h3>Gets an Order by ID</h3>
<p>GET /order/{orderId}</p>
