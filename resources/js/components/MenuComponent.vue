<template>
        <div>

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
        
        <li class="nav-item" v-if="rol_id == 1">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              <i class="fas fa-fw fa-balance-scale"></i>
              <span>Contabilidad</span>
            </a>
            <div id="collapseOne" class="collapse" aria-labelledby="headingFirst" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                <router-link class="collapse-item" to="/manual_seat">Asiento Manual</router-link>
                <router-link class="collapse-item" to="/collection_accounting">Recaudación Efectivo</router-link>
              </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseOne">
              <i class="fas fa-fw fa-asterisk"></i>
              <span>Generales</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingFirst" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                <router-link v-if="rol_id == 1" class="collapse-item" to="/contract">Contratos</router-link>
                <router-link class="collapse-item" to="/induction">Inducciones</router-link> 
                <router-link v-if="rol_id == 1 || rol_id == 4 || rol_id == 11" class="collapse-item" to="/tax">Iva</router-link>
                <router-link class="collapse-item" to="/settlement">Liquidaciones</router-link>             
                <router-link class="collapse-item" to="/patent" v-if="rol_id == 1 || rol_id == 4 || rol_id == 11">Patentes</router-link> 
                <router-link class="collapse-item" to="/videotutorial">Videotutoriales</router-link> 
              </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseTwo">
              <i class="fas fa-fw fa-battery-full"></i>
              <span>Gestión</span>
            </a>
            <div id="collapseThree" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                  <router-link class="collapse-item" to="/electronic_deposit" v-if="rol_id == 1 || rol_id == 4 || rol_id == 11">Depósitos</router-link>
                  <router-link class="collapse-item" to="/capitulation">Rendiciones</router-link>
                  <router-link class="collapse-item" to="/requirement" v-if="rol_id == 1 || rol_id == 4 || rol_id == 11">Requerimiento</router-link>
                  <router-link v-if="rol_id == 1 || rol_id == 4 || rol_id == 11" class="collapse-item" to="/bill_payment">Pago de Facturas</router-link>
              </div>
            </div>
        </li>

        <li class="nav-item" v-if="rol_id == 1 || rol_id == 4 || rol_id == 11">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseTwo">
              <i class="fas fa-fw fa-bell"></i>
              <span>DTE</span>
            </a>
            <div id="collapseFour" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                  <router-link class="collapse-item" to="/dte">Track de DTE</router-link>
                  <router-link class="collapse-item" to="/ticket">Boletas</router-link>
                  <router-link class="collapse-item" to="/bill">Facturas</router-link>
              </div>
            </div>
        </li>
        <li class="nav-item" v-if="rol_id == 1 || rol_id == 4 || rol_id == 11">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFive" aria-expanded="true" aria-controls="collapseTwo">
              <i class="fas fa-fw fa-bolt"></i>
              <span>Recaudaciones</span>
            </a>
            <div id="collapseFive" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                  <router-link class="collapse-item" to="/collection">Recaudaciones</router-link>
                  <router-link class="collapse-item" to="/electronic_collection">Recaudaciones Elect.</router-link>
              </div>
            </div>
        </li>
        <li class="nav-item" v-if="rol_id == 1 || rol_id == 11">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSix" aria-expanded="true" aria-controls="collapseThree">
              <i class="fas fa-fw fa-cog"></i>
              <span>Datos Maestros</span>
            </a>
            <div id="collapseSix" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                <router-link class="collapse-item" to="/bank">Bancos</router-link>
                <router-link v-if="rol_id == 1" class="collapse-item" to="/customer">Clientes</router-link>
                <router-link v-if="rol_id == 1" class="collapse-item" to="/received_dte">DTE Recibido</router-link>
                <router-link v-if="rol_id == 1" class="collapse-item" to="/transbank">Transbank</router-link>
              </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="true" aria-controls="collapseThree">
              <i class="fas fa-fw fa-check"></i>
              <span>Documentación</span>
            </a>
            <div id="collapseSeven" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="http://docs.jisparking.cl">Doc JIS</a>
              </div>
            </div>
        </li>
    </div>
</template>

<script>
    export default {
        created() {
          this.getRol();
        },
        methods: {
          getRol() {
                axios.get('/api/user?api_token='+App.apiToken)
                .then(response => {
                    console.log(response);
                    this.rol_id = response.data.data.rol_id;
                });
            }
        },
        data: function() {
          return {
            rol_id: this.rol_id
          }
        }

    }
</script>