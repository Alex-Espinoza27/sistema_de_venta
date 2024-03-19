<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Panel Administrativo</title>
        <!-- <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" /> -->
        <link href="<?php echo base_url;?>Assets/css/styles.css" rel="stylesheet" />
        <link href="<?php echo base_url;?>Assets/css/estilos.css" rel="stylesheet" />
        <link href="<?php echo base_url;?>Assets/css/select2.min.css" rel="stylesheet" />
        <link href="<?php echo base_url;?>Assets/DataTables/datatables.min.css" rel="stylesheet">
        <script src="<?php echo base_url;?>Assets/js/all.js"  ></script>


    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="<?php echo base_url;?>Administracion/home">Sistema de Venta</a>
            <!-- Navbar desplegar -->
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <!-- esto es para mostrar un modal:  data-bs-toggle="modal" data-bs-target="#cambiarPass" -->
                        <li><a class="dropdown-item" href="<?php echo base_url;?>Usuarios/informacion" >Perfil</a></li> 
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="<?php echo base_url;?>Usuarios/salir">Cerrar Sesión</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="card bg-dark p-2 ">
                            <i class="fas fa-user-circle fa-3x text-white my-2" ></i>
                            <!-- $data['nombre'] -->
                            <h5 class="text-center text-white">admin </h5>
                            <label class="text-center text-white">Admin</label>
                        </div>

                        <div class="nav">
                            <!-- ADMINISTRACION -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-cogs fa-2x"></i></div>
                                Administración
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="<?php echo base_url;?>Usuarios">Usuarios</a>
                                    <a class="nav-link" href="<?php echo base_url;?>Administracion">Configuración</a>
                                </nav>
                            </div>
                            <!-- PRODUCTOS -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseProducto" aria-expanded="false" aria-controls="collapseProducto">
                                <div class="sb-nav-link-icon"><i class="fab fa-product-hunt fa-2x"></i></div>
                                Productos
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseProducto" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="<?php echo base_url;?>Medidas">Medidas</a>
                                    <a class="nav-link" href="<?php echo base_url;?>Categorias">Categorias</a>
                                    <a class="nav-link" href="<?php echo base_url;?>Productos">Productos</a>
                                </nav>
                            </div>
                            <!-- CAJAS -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCaja" aria-expanded="false" aria-controls="collapseCaja">
                                <div class="sb-nav-link-icon"><i class="fas fa-box fa-2x"></i></div>
                                Cajas
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseCaja" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="<?php echo base_url;?>Cajas">Cajas</a>
                                    <a class="nav-link" href="<?php echo base_url;?>Cajas/arqueo">Arqueo Cajas</a>
                                </nav>
                            </div>
                            <!-- CLIENTE -->
                            <a class="nav-link" href="<?php echo base_url;?>Clientes" >
                                <div class="sb-nav-link-icon"><i class="fas fa-users fa-2x"></i></div>
                                Clientes
                            </a> 
                            <!-- ENTRADAS -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseEntradas" aria-expanded="false" aria-controls="collapseEntradas">
                                <div class="sb-nav-link-icon"><i class="fas fa-shipping-fast fa-2x"></i></div>
                                Entradas
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseEntradas" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="<?php echo base_url;?>Compras">Nueva Compra</a>
                                    <a class="nav-link" href="<?php echo base_url;?>Compras/historial">Historial Compra</a>
                                </nav>
                            </div>
                            <!-- VENTAS -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseVentas" aria-expanded="false" aria-controls="collapseVentas">
                                <div class="sb-nav-link-icon"><i class="fas fa-cash-register fa-2x"></i></div>
                                Ventas
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseVentas" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="<?php echo base_url;?>Compras/ventas">Ventas</a>
                                    <a class="nav-link" href="<?php echo base_url;?>Compras/historial_ventas">Historial Ventas</a>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Bienvenido:</div>
                        Sistema de venta ALEX
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4 mt-4">
                        
                    
