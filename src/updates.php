<?php
session_start();
if (!isset($_SESSION['priviledges_level']) || $_SESSION['priviledges_level'] != 1) {
    echo 'Unable to authenticate';
    header('Location: /login');
    die;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/tachyons.css"/>
	<link rel="stylesheet" href="../css/main.css" />
    <title>Wedding - RSVP Status</title>
</head>
<body>
<section id="app" class="center w-60-l w-70-m mw-60-l pa4-l pa2 sans-serif">
    <main class="flex flex-row justify-between items-center w-100 ph3-l ph1">

        <h1>RSVP Status</h1>
        <div class="flex flex-row items-center">
        <a href="/src/admin.php" class="pv2 ph3 br3 ba b--black-10 bg no-underline bg-near-white o-70 glow black mr3">← Get links</a>
        <div class="loading-blip "></div>
        </div>
    </main>
    <div class="bg-near-white  w-100 mv3 br3 ph3-l ph3-m ph1 flex flex-column items-start">
        <div class="flex flex-row items-center">
            <h3 class="pr3">Total invited: </h3>
            <h3 class="br bw1 pr3" v-if="$store.state.currentList != ''">{{totalCountInvited}}</h3>
        <h3 class="ph3">Confirmed Guests: </h3>
        <h3 class="br bw1 pr3" v-if="$store.state.currentList != ''">{{totalCount}}</h3>
        <h3 class="ph3">RSVPs Outstanding: </h3>
        <h3 v-if="$store.state.currentList != ''">{{outstandingRSVPs}}</h3>
        </div>

        <div class="flex flex-row items-center">

        </div>
    </div>
    <section>
        <div class="flex flex-row ph3-l ph3-m ph1 " >
            <table class="w-100"> 
                <thead>
                    <tr>
                        <th class="fw6 bb b--black-20 tl pb3 pr3 bg-white">Name</th>
                        <th class="fw6 bb b--black-20 tl pb3 pr3 bg-white">Email</th>
                        <th class="fw6 bb b--black-20 tc pb3 pr3 bg-white">Total</th>
                        <th class="fw6 bb b--black-20 tr pb3 pr3 bg-white">Confirmed?</th>
                    </tr>
                </thead>
                <tr v-if="$store.state.currentList.guests.length > 0" v-for="item, index in $store.state.currentList.guests">
                <td class="pv2 pr3 bb b--black-20">{{item.guest_name}}</td>
                <td class="pv2 pr3 bb b--black-20">{{item.guest_email_address}}</td>
                <td class="pv2 pr3 tc bb b--black-20">{{item.guest_attending}}</td>
                <td class="pv2 bb tr b--black-20 flex flex-row items-center justify-end" ><span class="br-pill black ph3-l ph2 pv2-l pv1" :class="(item.guest_rsvp_confirmed ? 'active' : 'inactive')" v-text="(item.guest_rsvp_confirmed ? 'Yes' : 'No')"></span><span class="ph3 pv2 f3" v-text="(item.guest_rsvp_confirmed && item.guest_attending != 0 ? '👰' : '🙅‍♀️')"></span></td>
                </tr>
            </table>
            </div>
        </div>
    </section>
</section>

<!-- Vue JS-->
<script src="../js/vue.js"></script>
<script src="../js/vuex.js"></script>
<script src="../js/vue-router.js"></script>
<!-- End Vue JS-->

<script>

// Define Routes
// let routes   = [
//     {path : '/upload_files', component : upload_files},
// ]

// Define Router
// const router = new VueRouter({
//     routes
// });

// Define VueX app state management
const store = new Vuex.Store({
    state : {
        newList : [],
        currentList : "",
        IDerror : [],
        IDsuccess : [],
    },
    actions : {
        FETCH_GUESTS(state, payload) {
            let formData = new FormData();
                formData.append('requestPayload', JSON.stringify({request : 'getGuests'}));
                let result = fetch('/src/controller.php', {
                        method  : 'POST',
                        mode    : 'no-cors',
                        body    : formData
                }).then(e => {return e.json()})
                  .then(e =>{
                    if (e.success != false)  {
                        store.commit("ADD_GUESTS_TO_STATE", {guests : e.guests});       
                        }
                    else {
                        store.commit("ADD_ID_ERROR", {guestEmail : payload.guestEmail})
                        }
                    });    
        },
    },
    mutations : {
        CLEAR_INPUT(state,payload) {
            store.state.currentList = ""
        },
        ADD_GUESTS_TO_STATE(state, payload) {
            store.state.currentList = payload;
        },
        ADD_ID_ERROR(state,payload) {
            store.state.IDerror.push(payload.guestEmail);
        },
        ADD_ID_SUCCESS(state,payload) {
            store.state.IDsuccess.push({email : payload.guestEmail, id : payload.id });
        }
    }
})


// Define Vue App
const app = new Vue({
    el: '#app',
    // router,
    store,
    data : {
        newList : ""
    },
    methods : {
        importListFromText : (input)=>{
            let perLineSplit    = input.split("\n");
            let processListFull = perLineSplit.map((row)=>{
            let invitee = row.split(",");
            let newGuest = {
                guestName : invitee[0],
                guestEmail : invitee[1],
                guestCount : invitee[2]
            }
            store.commit("ADD_LIST_TO_STATE", {newGuest : newGuest});
            });
            // let myObj = Object.fromEntries(processListFull);
            
            store.commit("CLEAR_INPUT");
        }, 
        forcerender : ()=>{
            //
        }
    },
    computed : {
        totalCount :()=>{
            let total = 0;
            store.state.currentList.guests.map((guest)=>{total = total + guest.guest_attending});
            return total;
        },
        totalCountInvited :()=>{
            let total = 0;
            store.state.currentList.guests.map((guest)=>{total = total + guest.guest_invited_total});
            return total;
        },
        outstandingRSVPs :()=>{
            let total = 0;
            store.state.currentList.guests.map((guest)=>{total = total + guest.guest_rsvp_confirmed});
            total = store.state.currentList.guests.length  - total;
            return total;
        }
    },
    mounted :()=>{
        setInterval(() => {
            store.dispatch("FETCH_GUESTS");
        }, 3000);
    }
})


</script>

</body>
</html>