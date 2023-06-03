define(['jquery', 'underscore', 'twigjs', 'lib/components/base/modal'], function ($, _, Twig, Modal) {
    var CustomWidget = function () {
        var self = this, system = self.system;

        this.callbacks = {

            render: function () {

                if (self.system().area == "lcard") {

                    self.productsTable = '';

                    let rowsProducts = '';

                    $.getJSON('https://test.mebcrm.ru/products.php', {
                            'lead_id': AMOCRM.data.current_card.id
                        },
                        function (products) {
                            if (products.length === 0) {
                                rowsProducts = '<tr><td colspan="2">Товаров нет</td></tr>';
                            }else if(products.error ){
                                rowsProducts = '<tr><td colspan="2">{products.error}</td></tr>';
                            }else {
                                for (let product of products) {
                                    rowsProducts += '<tr><td>' + product.name + '</td><td>' + product.quantity + '</td></tr>';
                                }
                            }
                            let thead = '<thead><tr><th>Название</th><th>Количество</th></tr></thead>';
                            let tbody = '<tbody>' + rowsProducts + '</tbody>';
                            self.productsTable = '<table>' + thead + tbody + '</table>';
                        });

                    let $widgets_block = $('#widgets_block');

                    if ($widgets_block.find('#show_products_button').length == 0) {
                        $widgets_block.append(
                            self.render({ref: '/tmpl/controls/button.twig'}, {
                                id: 'show_products_button',
                                text: 'Посмотреть товары'
                            })
                        );
                    }
                }
                return true;
            },

            init: function () {
                console.log('init');
                return true;
            },
            bind_actions: function () {
                console.log('bind_actions');
                console.log(self.productsTable)
                $('#widgets_block #show_products_button').on('click', function () {
                    var modal = new Modal({
                        class_name: 'products-modal-window',
                        init: function ($modal_body) {
                            $modal_body
                                .trigger('modal:loaded')
                                .html(self.productsTable) // добавляю данные
                                .trigger('modal:centrify');
                        },
                        destroy: function () {
                        }
                    });
                });

                return true;
            },

            settings: function () {
                return true;
            },
            onSave: function () {
                return true;
            },
            destroy: function () {
                console.log('destroy');
            }
        };

        return this;
    };

    return CustomWidget;
});