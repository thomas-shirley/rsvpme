<?php
session_start();
if (!isset($_SESSION['priviledges_level']) || $_SESSION['priviledges_level'] != 1) {
    echo 'Unable to authenticate';
    header('Location: /login');
    die;
}
else {
    
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
    <title>RSVPME - Generate Links</title>
</head>
<body>
<section id="app" class="center w-60-l w-70-m w-100 mw-60-l pa4 sans-serif">
    <main>
        <div class="flex flex-row justify-between items-center">
        <h1>Add guests and generate links</h1>
        <a href="/src/updates.php" class="pv2 ph3 br3 ba b--black-10 bg no-underline bg-near-white o-70 glow black">See live resuts</a>
        </div>
        <div class="flex flex-column">
            <p class="pv0 mt0 mb2 f6 gray">Enter comma separated values as:</p>
            <p class="pt0 mt0">Guest names, Guest E-mail, Guest total</p>
            <textarea class="ba b--black-30 br2"rows="10" @input="forcerender" v-model="$store.state.currentList"></textarea>
            <div class="mv3">
                <button class="bg-white mv1 sans-serif bw1 pa2 b--black-10 br3" @click="importListFromText($store.state.currentList)">Generate Links</button>
                <!-- <button v-if="$store.state.newList.length" class="bg-white mv1 sans-serif bw1 pa2 b--black-10 br3" @click="generateIDs">Generate</button> -->
            </div>
        </div>
        <div>
            <div class="flex flex-row">
            <p class="fw5 pr2">Total e-mails:</p>
            <p v-text="$store.state.existingList.length"></p>
            </div>
        </div>
    </main>

    <div class="flex flex-row  " >
        <div class="w-100">
                <div class="flex flex-row">
                    <p class="fw6 bb b--black-20 tl pb3 pr3 bg-white">Name</p>
                    <p class="fw6 bb b--black-20 tl pb3 pr3 bg-white">Link</p>
                    <!-- <p class="fw6 bb b--black-20 tl pb3 pr3 bg-white">Email</p> -->
                    <p class="fw6 bb b--black-20 tc pb3 pr3 bg-white">Total Invited</p>
                    
                </div>
            
            <div class="bb mv2 pv2 b--black-20 flex flex-row-ns flex-column justify-between" v-if="$store.state.existingList.length > 0" v-for="item, index in $store.state.existingList">
            <p class="pv2-l f6 pv0 mv0 pr3 w-30-l">{{item.guest_name}}</p>
            <p class="pv2-l pv0 mv2 f6 pr3 blue w-50-l w-100"><a v-bind:href="rsvpUrlRender(item.guest_rsvp_number)" v-text="rsvpUrlRender(item.guest_rsvp_number)"></a></p>
            <!-- <p class="pv2 pr3 ">{{item.guest_email_address}}</p> -->
            <p class="pv2-l f6 pv0 mv0 pr3 ">{{item.guest_invited_total}} invitees</p>
            
            </div>
    </div>
        </div>

</section>

<!-- Vue JS-->
<script src="../js/vue.js"></script>
<script src="../js/vuex.js"></script>
<script src="../js/vue-router.js"></script>
<!-- End Vue JS-->

<script>


// Define VueX app state management
const store = new Vuex.Store({
    state : {
        newList : [],
        existingList : [],
        currentList : "",
        IDerror : [],
        IDsuccess : [],
    },
    actions : {
        ADD_TO_DB_GENERATE_ID(state, payload) {
            let formData = new FormData();
                formData.append('requestPayload', JSON.stringify({request : 'generateID', guestEmail : payload.guestEmail}));
                formData.append('guestEmail', payload.guestEmail);
                formData.append('guestName', payload.guestName);
                formData.append('guestCount', payload.guestCount);
                let result = fetch('/src/controller.php', {
                        method  : 'POST',
                        mode    : 'no-cors',
                        body    : formData
                }).then(e => {return e.json()})
                  .then(e =>{
                    if (e.success != false)  {
                        store.commit("ADD_ID_SUCCESS", {guestEmail : payload.guestEmail, id : e.id});    
                        store.dispatch("FETCH_GUESTS");
                        store.commit("CLEAR_LIST")
                        }
                    else {
                        store.commit("ADD_ID_ERROR", {guestEmail : payload.guestEmail})
                        }
                    });    
        },
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
        ADD_LIST_TO_STATE(state, payload) {
            store.state.newList.push(payload.newGuest);
        },
        ADD_ID_ERROR(state,payload) {
            store.state.IDerror.push(payload.guestEmail);
        },
        ADD_ID_SUCCESS(state,payload) {
            store.state.IDsuccess.push({email : payload.guestEmail, id : payload.id });
        },
        ADD_GUESTS_TO_STATE(state, payload) {
            store.state.existingList = payload.guests;
        },
        CLEAR_LIST(state,payload){
            store.state.newList = [];
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
    watch : {

    },
    methods : {
        importListFromText : (input)=>{
            if (input === "") {return;}
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
            store.state.newList.forEach(element => {
                store.dispatch("ADD_TO_DB_GENERATE_ID", {guestEmail : element.guestEmail, guestCount : element.guestCount, guestName : element.guestName});
            });
            store.commit("CLEAR_INPUT");
            
        }, 
        rsvpUrlRender : (input)=>{
            return window.location.origin +'/rsvp/'+ input
            
        },
        forcerender : ()=>{
            //
        },

    },
    mounted : ()=>{
        store.dispatch("FETCH_GUESTS");
        
    }
})


</script>

</body>
</html>