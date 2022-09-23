jQuery(function($) {

    // показывать html форму при нажатии кнопки «Обновить товар»
    $(document).on("click", ".update-product-button", function() {

        // получаем ID товара
        var id = $(this).attr("data-id");
        // читаем одну запись на основе данного идентификатора товара
        $.getJSON("http://rest-api/api/magazine/read_one.php?id=" + id, function(data) {

            // значения будут использоваться для заполнения нашей формы
            var title = data.title;
            var images = data.images;
            var description = data.description;
            var id_author = data.id_author;
            var surname_author = data.surname_author;
            var name_author = data.name_author;
            var lastname_author = data.lastname_author;

            // загрузка списка категорий
            $.getJSON("http://rest-api/api/author/read.php", function(data) {

                // строим список выбора
                // перебор полученного списка данных
                var authors_options_html=`<select name="id_author" class="form-control">`;

                $.each(data.records, function(key, val){
                    // опция предварительного выбора - это идентификатор категории
                    if (val.id==id_author) {
                        authors_options_html+=`<option value="` + val.id + `" selected>` + val.surname + ` ` + val.name + ` ` + val.lastname + `</option>`;
                    } else {
                        authors_options_html+=`<option value="` + val.id + `">` + val.surname + ` ` + val.name + ` ` + val.lastname + `</option>`; 
                    }
                });
                authors_options_html+=`</select>`;

                // сохраним html в переменной «update product»
                var update_product_html=`
                    <div id="read-products" class="btn btn-primary pull-right m-b-15px read-products-button">
                        <span class="glyphicon glyphicon-list"></span> Все журналы
                    </div>

                    <!-- построение формы для изменения товара -->
                    <!-- мы используем свойство "required" html5 для предотвращения пустых полей -->
                    <form id="update-product-form" action="#" method="post" border="0">
                        <table class="table table-hover table-responsive table-bordered">

                            <tr>
                                <td>Название</td>
                                <td><input value=\"` + title + `\" type="text" name="title" class="form-control" required /></td>
                            </tr>

                            <tr>
                                <td>Изображение</td>
                                <td><input value=\"` + images + `\" type="text" name="images" class="form-control" /></td>
                            </tr>

                            <tr>
                                <td>Описание</td>
                                <td><textarea name="description" class="form-control" required>` + description + `</textarea></td>
                            </tr>

                            <tr>
                                <td>Автор</td>
                                <td>` + authors_options_html + `</td>
                            </tr>

                            <tr>
                                <!-- скрытый «идентификатор продукта», чтобы определить, какую запись удалить -->
                                <td><input value=\"` + id + `\" name="id" type="hidden" /></td>

                                <!-- кнопка отправки формы -->
                                <td>
                                    <button type="submit" class="btn btn-info">
                                        <span class="glyphicon glyphicon-edit"></span> Обновить журнал
                                    </button>
                                </td>
                            </tr>

                        </table>
                    </form>
                `;

                // добавим в «page-content» нашего приложения
                $("#page-content").html(update_product_html);

                // изменим title страницы
                changePageTitle("Обновление журнала");
            });
        });
    });

    // будет запущен при отправке формы обновления товара
    $(document).on("submit", "#update-product-form", function() {

        // получаем данные формы
        var form_data=JSON.stringify($(this).serializeObject());

        // отправка данных формы в API
        $.ajax({
            url: "http://rest-api/api/magazine/update.php",
            type : "POST",
            contentType : "application/json",
            data : form_data,
            success : function(result) {
                // продукт был создан, возврат к списку продуктов
                var json_url="http://rest-api/api/magazine/read_paging.php";
                showProducts(json_url);
            },
            error: function(xhr, resp, text) {
                // вывод ошибки в консоль
                console.log(xhr, resp, text);
            }
        });

        return false;
    });
    
});