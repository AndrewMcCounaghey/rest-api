jQuery(function($){

    // показать html форму при нажатии кнопки «создать товар»
    $(document).on("click", ".create-product-button", function() {
        // загрузка списка категорий
        $.getJSON("http://rest-api/api/author/read.php", function(data) {
            // перебор возвращаемого списка данных и создание списка выбора
            var authors_options_html=`<select name="id_author" class="form-control">`;
            $.each(data.records, function(key, val){
                authors_options_html+=`<option value="` + val.id + `">` + val.surname + ` ` + val.name + ` ` + val.lastname + `</option>`;
            });
            authors_options_html+=`</select>`;
            var create_product_html=`
                <!-- кнопка для показа всех товаров -->
                <div id="read-products" class="btn btn-primary pull-right m-b-15px read-products-button">
                    <span class="glyphicon glyphicon-list"></span> Все товары
                </div>
                <!-- html форма «Создание журнала -->
                <form id="create-product-form" action="#" method="post" border="0">
                    <table class="table table-hover table-responsive table-bordered">

                        <tr>
                            <td>Название</td>
                            <td><input type="text" name="title" class="form-control" required /></td>
                        </tr>

                        <tr>
                            <td>Изображение</td>
                            <td><input type="file" id="image" name="images" onchange="checkFileSize();" class="form-control" /></td>
                        </tr>

                        <tr>
                            <td>Описание</td>
                            <td><textarea name="description" class="form-control" required></textarea></td>
                        </tr>

                        <!-- список выбора авторов -->
                        <tr>
                            <td>Авторы</td>
                            <td>` + authors_options_html + `</td>
                        </tr>

                        <!-- кнопка отправки формы -->
                        <tr>
                            <td></td>
                            <td>
                                <button type="submit" class="btn btn-primary">
                                    <span class="glyphicon glyphicon-plus"></span> Создать журнал
                                </button>
                            </td>
                        </tr>

                    </table>
                </form>`;
            // вставка html в «page-content» нашего приложения
            $("#page-content").html(create_product_html);

            // изменяем тайтл
            changePageTitle("Создание журнала");
            var uploadField = document.getElementById("image");

            uploadField.onchange = function() {
                if(this.files[0].size > 2097152){
                   alert("File is too big!");
                   this.value = "";
                };
                console.log(this.value);
            };
        });
    });
    

    // будет работать, если создана форма товара
    $(document).on("submit", "#create-product-form", function(){
        // получение данных формы
        var form_data=JSON.stringify($(this).serializeObject());
        console.log(form_data);
        // отправка данных формы в API
        $.ajax({
            url: "http://rest-api/api/magazine/create.php",
            type : "POST",
            processData: false,
            contentType : false,
            data : form_data,
            success : function(result) {
                // продукт был создан, вернуться к списку продуктов
                console.log(result);
                var json_url="http://rest-api/api/magazine/read_paging.php";
                showProducts();
            },
            error: function(xhr, resp, text) {
                // вывести ошибку в консоль
                console.log(xhr, resp, text);
            }
        });
        
        return false;
    });

});