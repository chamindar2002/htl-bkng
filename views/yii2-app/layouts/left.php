<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p> <?= isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : 'Guest' ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Room Calendar', 'icon'=>'fa fa-calendar', 'url'=>'/dashboard'],
                    ['label' => 'Dashboard', 'icon'=>'fa fa-tachometer', 'url'=>'/grc/booking/dashboard'],
                    ['label' => 'Orders', 'icon'=>'fa fa-beer', 'url'=>'/inventory/orders'],
                    ['label'=>'Guest Management', 'icon'=>'fa fa-address-card', 'url'=>'#',
                        'items'=>[
                            ['label'=>'Agents', 'icon'=>'fa fa-handshake-o', 'url'=>'/grc/agent'],
                            ['label'=>'Meal Plans', 'icon'=>'fa fa-cutlery', 'url'=>'/grc/meal-plan'],
                            ['label'=>'Packages', 'icon'=>'fa fa-bed', 'url'=>'/grc/package'],
                            ['label'=>'Guests', 'icon'=>'fa fa-address-book-o', 'url'=>'/grc/guest'],
                        ]
                    ],
                    ['label'=>'Inventory Management', 'icon'=>'fa fa-cubes', 'url'=>'#',
                        'items'=>[
                            ['label'=>'Departments', 'icon'=>'fa fa-building', 'url'=>'/inventory/invn-department'],
                            ['label'=>'Categories', 'icon'=>'fa fa-sitemap', 'url'=>'/inventory/invn-category'],
                            ['label'=>'Inventory Items', 'icon'=>'fa fa-shopping-cart', 'url'=>'/inventory/invn-item-master'],
                        ]
                    ],
                    ['label'=>'Auth Management', 'icon'=>'fa fa-key', 'url'=>'#',
                        'items'=>[
                            ['label'=>'Users', 'icon'=>'fa fa-user-o', 'url'=>'/admin/user'],
                            ['label'=>'Roles', 'icon'=>'fa fa-users', 'url'=>'/admin/role'],
                            ['label'=>'Role Assignment', 'icon'=>'fa fa-user-plus', 'url'=>'/admin/assignment'],

                        ], 'visible'=>Yii::$app->user->can('permission_admin')
                    ],

                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    [
                        'label' => 'Same tools',
                        'icon' => 'fa fa-share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug'],],
                            [
                                'label' => 'Level One',
                                'icon' => 'fa fa-circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Level Two', 'icon' => 'fa fa-circle-o', 'url' => '#',],
                                    [
                                        'label' => 'Level Two',
                                        'icon' => 'fa fa-circle-o',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'fa fa-circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'fa fa-circle-o', 'url' => '#',],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
