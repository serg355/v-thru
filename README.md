<h1>Установка</h1>
<p>Клонируйте репозиторий в папку v_thru.</p>
<p>Исполльзуйте composer для установки Symfony и пакетов используя готовый файл composer.json.</p>
<p>В задаче используется PHP 7.2.34</p>

<h1>Описание</h1>
<p>Тестирование эндпоинтов проводил через Postman</>

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
