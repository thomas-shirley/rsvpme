<?php
    if (!require 'config.php') {
        throw new Exception("Config file not found.", 1);
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/tachyons.css"/>
    <link rel="stylesheet" href="../css/main.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;500&family=Noto+Serif+Display:wght@500&display=swap" rel="stylesheet">
    <title>RSVP - Wedding</title>
</head>
<body class="bg-faint-gold">
 <div class="z-0 w-100 items-center center fixed flex flex-row justify-between">
    <!-- lines, pure aesthetics -->
    <div class="z-0 w-1px min-vh-100"></div>
    <div class="z-0 w-1px min-vh-100"></div>
    <div class="z-0 w-1px min-vh-100 o-0"></div>
    <div class="z-0 w-1px min-vh-100"></div>
    <div class="z-0 w-1px min-vh-100 o-0"></div>
    <div class="z-0 w-1px min-vh-100"></div>
    <div class="z-0 w-1px min-vh-100"></div>
 </div>   

<section id="app" class="tc ph5-ns z1">
<!-- v-if="$store.state.recentRSVP == true && $store.state.guestAttending != 0 -->
<div v-cloak v-if="$store.state.recentRSVP == true" class=" fade-in-delayed fixed flex flex-column items-center justify-center z3 w-100 min-vh-100 bg-white">
<h1 class="mw7 tc noto f-headline-l f fw1 pa0 tracked-tight mv0 pv0"><?php echo $WEDDING_DATE ?></h1>
<img class="pv4 mw5" src="../images/thanks.svg" alt="Thanks"/>
<div class="inter f5 lh-copy charcoal-wedding mb4 mt1 relative"  v-if="$store.state.recentRSVP == true && $store.state.guestAttending != 0">
      <p class="black f5 ph3 mv4 lh-copy ">Superb, we can't wait to see you. Your invitation is on the way.</p>
      </div>
      <div class="inter f5 lh-copy charcoal-wedding mb4 mt1 relative"  v-if="$store.state.recentRSVP == true && $store.state.guestAttending == 0">
      <p class="black f5 ph3 mv4 lh-copy">Thanks for letting us know, you'll be missed.</p>
      </div>
</div>

  <article class=" w-100 w-60-l w-80-m center relative pb6">

    <div class="pa2 pt4 pb0 z2">
      <h1 class="mw7 tc noto f-headline-l f fw1 pa0 tracked-tight mv0 pv0"><?php echo $WEDDING_DATE ?></h1>
      <h1 class="alexander-lettering mw7 tc jumbo-text fw1 pa0 tracked-tight mv0 pv0"><?php echo $WEDDING_NAME ?></h1>
      <p v-if="$store.state.guestRSVP != true" class="inter tc mw6 w-80 center f5 lh-copy charcoal-wedding mb3 mt3 fw4 relative">{{$store.state.guestName}} — we would be thrilled for you to join us for our wedding.</p>      
      <p class="inter f5 lh-copy charcoal-wedding mb4 mt1 underline relative"><?php echo $WEDDING_VENUE ?>
      
      <div v-cloak class="mw5 w-60-m w-60 w-50-l center flex flex-column" v-if="!$store.state.errors.length > 0 && $store.state.guestRSVP != true && $store.state.recentRSVP != true">
        <a @click="setRSVP(2)" v-if="$store.state.guestInvitedTotal > 1" class="black inter fw2 br2 f4 link tc ph3 mv1 pa3 rsvp-2 " href="#">RSVP, Yes</a>
        <a @click="setRSVP(1)" v-if="$store.state.guestInvitedTotal < 2" class="black inter fw2 br2 f4 link tc ph3 mv1 pa3 rsvp-2 " href="#">RSVP, Yes</a>
        <a @click="setRSVP(1)" v-if="$store.state.guestInvitedTotal > 1" class="black inter fw2 br2 f4 link tc ph3 mv1 pa3  rsvp-1" href="#">RSVP, Just for one</a>
        <a @click="setRSVP(0)" class="black inter fw2 br2 f4 link tc ph3 mv1 pa3 rsvp-0" href="#"><span v-if="$store.state.guestInvitedTotal != 1">We</span><span v-if="$store.state.guestInvitedTotal < 2">I</span> can't make it</a>      
     </div>
    <p v-if="$store.state.guestRSVP != true && !$store.state.errors.length && $store.state.recentRSVP != true"  class="mt4 mb2 f7 fw1 o-60 charcoal-wedding pb2 sans-serif fw6">Full details to follow.</p>
    <p v-if="$store.state.guestRSVP != true && !$store.state.errors.length && $store.state.recentRSVP != true" class="mt1 w-80 center mb3 f7 fw1 o-60 charcoal-wedding pb2 mb2 sans-serif fw6 lh-copy">Owing to our chosen venue, we are unable to accommodate children.</p>  
      <div>
        <!-- errors -->
        <p v-for="item, index in $store.state.errors" v-if="$store.state.errors.length > 0" class="red f4 ph3 sans-serif lh-copy mv4 lh-copy">{{item}}</p>
      </div>
      <div>
        <!-- already confirmed -->
        <p  v-if="$store.state.guestRSVP == true && $store.state.guestAttending != 0" class="inter black f5 ph3 mv4 lh-copy">You have already replied. We look forward to seeing <span v-if="$store.state.guestAttending < 2">just one of you.</span> <span v-if="$store.state.guestAttending > 1">both of you.</span></p>
        <p  v-if="$store.state.guestRSVP == true && $store.state.guestAttending == 0" class="inter black f5 ph3 mv4 lh-copy">You have already replied and we will miss you.</p>
      </div>
    </div>
  </article>
</section>

<!-- Vue JS-->
<script src="../js/vue.js"></script>
<script src="../js/vuex.js"></script>
<!-- End Vue JS-->

<script>

// Define VueX app state management
const store = new Vuex.Store({
    state : {
        guestID : null,
        guestEmail : null,
        guestRSVP : null,
        recentRSVP : false,
        guestAttending : null,
        guestInvitedTotal : null,
        guestName : null,
        errors : [],
    },
    actions : {
        UPDATE_RSVP(state, payload) {
            let formData = new FormData();
                formData.append('requestPayload', JSON.stringify({request : 'updateRSVP'}));
                formData.append('guestID', payload.guestID);
                formData.append('rsvpCount', payload.guestAttending);
                let result = fetch('/src/controller.php', {
                        method  : 'POST',
                        mode    : 'no-cors',
                        body    : formData
                }).then(e => {return e.json()})
                  .then(e =>{
                    if (e.success != false)  {
                        store.commit("SUCCESS_CONFIRM_RSVP_NUMBER", {guestEmail : payload.guestEmail, rsvpCount : payload.guestAttending});       
                        }
                    else {
                        store.commit("ERROR_CONFIRM_RSVP_NUMBER", {guestEmail : payload.guestEmail, rsvpCount : e.rsvpCount});       
                        }
                    });    
        },
        GET_GUEST_EMAIL(state, payload){
            let formData = new FormData();
                formData.append('requestPayload', JSON.stringify({request : 'getGuestEmailAddress'}));
                formData.append('guestID', payload.guestID);
                let result = fetch('/src/controller.php', {
                        method  : 'POST',
                        mode    : 'no-cors',
                        body    : formData
                }).then(e => {return e.json()})
                  .then(e =>{
                    if (e.success != 'error')  {
                        console.log(e)
                        store.commit("SET_GUEST_EMAIL", {guestEmail : e.guestEmail});       
                        store.commit("SET_GUEST_RSVP", {guestRSVP : e.guestConfirmed});       
                        store.commit("SET_GUEST_ATTENDING", {guestsAttending : e.guestsAttending});       
                        store.commit("SET_GUEST_NAME", {guestName : e.guestName});       
                        store.commit("SET_GUEST_INVITED_TOTAL", {guestInvitedTotal : e.guestsInvited});       
                        }
                    else {
                        console.log('error');
                        store.commit("ERROR_SETTING_EMAIL");       
                        }
                    });  
        },
        CONFIRM_RSVP_NUMBER(state, payload) {
            let formData = new FormData();
                formData.append('requestPayload', JSON.stringify({request : 'confirmRSVP'}));
                formData.append('guestID', payload.guestID);
                formData.append('guestAttending', payload.guestAttending);
                let result = fetch('/src/controller.php', {
                        method  : 'POST',
                        mode    : 'no-cors',
                        body    : formData
                }).then(e => {return e.json()})
                  .then(e =>{
                    if (e.success != 'error')  {
                        // Show thank you message.
                        }
                    else {
                        console.log('error');
                        // store.commit("ERROR_SETTING_EMAIL");       
                        }
                    }); 
        }
    },
    mutations : {
        SET_RSVP_ID(state,payload) {
            store.state.guestID = payload;
        },
        SET_GUEST_EMAIL(state,payload) {
            store.state.guestEmail = payload.guestEmail;
        },
        SET_GUEST_RSVP(state,payload) {
            store.state.guestRSVP = payload.guestRSVP;
        },
        SET_GUEST_ATTENDING(state,payload) {
            store.state.guestAttending = payload.guestsAttending;
        },
        SET_GUEST_INVITED_TOTAL(state,payload) {
            store.state.guestInvitedTotal = payload.guestInvitedTotal;
        },
        SET_GUEST_NAME(state,payload) {
            store.state.guestName = payload.guestName;
        },
        ERROR_SETTING_EMAIL(state,payload) {
            store.state.errors.push("There was a problem with your link. Try again?");
        },
        SUCCESS_CONFIRM_RSVP_NUMBER(state,payload) {
            // store.state.guestRSVP = true;
            store.state.recentRSVP = true;
            store.commit("SET_GUEST_ATTENDING",{guestsAttending : payload.rsvpCount})
            //fade out page elements.
            //fade in thankyous.
        },
        ERROR_CONFIRM_RSVP_NUMBER(state,payload) {

        }
    }
})


// Define Vue App
const app = new Vue({
    el: '#app',
    store,
    data : {
        newList : ""
    },
    methods : {
        setRSVP :(number)=>{
            console.log(number)
            store.dispatch("UPDATE_RSVP",{guestID : store.state.guestID, guestAttending : number});
        }

    },
    created:()=>{
      //get RSVP ID from Url
      store.commit("SET_RSVP_ID", (window.location.pathname.split("/rsvp/").pop()));
      store.dispatch("GET_GUEST_EMAIL",{guestID : store.state.guestID} );
    }
})


</script>
</body>
</html>