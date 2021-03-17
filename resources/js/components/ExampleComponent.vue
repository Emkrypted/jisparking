<template>
    <div>
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <img src="@{{ url('public/backend/img/logo.png') }}" id="logo" style="height: 70px;">
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <router-link class="nav-link" to="/account">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Inicio</span></router-link>
    </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
          Menú
        </div>
          <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              <i class="fas fa-fw fa-cog"></i>
              <span>Contabilidad</span>
            </a>
            <div id="collapseOne" class="collapse" aria-labelledby="headingFirst" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                <router-link class="collapse-item" to="/collection_accounting">Recaudación Efectivo</router-link>
                <router-link class="collapse-item" to="/transbank_collection_accounting">Recaudación Tarjeta</router-link>
              </div>
            </div>
          </li>

          <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
              <i class="fas fa-fw fa-cog"></i>
              <span>Gestión</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                  <router-link class="collapse-item" to="/dte">DTE</router-link>
                  <router-link class="collapse-item" to="/electronic_deposit">Depósitos</router-link>
                  <router-link class="collapse-item" to="/collection">Recaudaciones</router-link>
                  <router-link class="collapse-item" to="/electronic_collection">Recaudaciones Elect.</router-link>
                  <router-link class="collapse-item" to="/capitulation">Rendiciones</router-link>
                  <router-link class="collapse-item" to="/request">Requerimiento</router-link>
                  <router-link class="collapse-item" to="/transbank">Transbank</router-link>
                  <router-link class="collapse-item" to="/bill_payment">Pago de Facturas</router-link>
              </div>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
              <i class="fas fa-fw fa-cog"></i>
              <span>Datos Maestros</span>
            </a>
            <div id="collapseThree" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                <router-link class="collapse-item" to="/bank">Bancos</router-link>
                <router-link class="collapse-item" to="/cashier">Cajeros</router-link>
                <router-link class="collapse-item" to="/charge">Cargos</router-link>
                <router-link class="collapse-item" to="/contract">Contratos</router-link>
                <router-link class="collapse-item" to="/tax">Iva</router-link>
                <router-link class="collapse-item" to="/customer">Usuarios</router-link>
                <router-link class="collapse-item" to="/branch_office">Sucursales</router-link>
                <router-link class="collapse-item" to="/contract_type">Tipos de Contratos</router-link>
              </div>
            </div>
          </li>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
