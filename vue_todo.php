<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width">
    <title>Live</title>
    	<base href="http://dev.creativearkllc.com/">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Rubik&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <style>
			body { min-width:320px  }
            td { font-size: 1.2em ; vertical-align:center;}
            ::selection { background: yellow}
            [v-cloak] { display:none; }
            .table tr:nth-child(1) td { border-top: 0px }
            .table { margin-bottom:5px;}
            td {  word-break: break-all;
            }


             * {font-family: 'Rubik' }
            .strikeout { text-decoration:line-through}
			.fa-blank
			{
			visibility:hidden !important;
			}
    </style>
</head>
<body>

    <div id="app">


                <div class="jumbotron">



                        <div id = "intro" style = "text-align:center;">
						<h2 class="display-3" v-cloak>{{ timestamp }}</h2>
						<h2 v-cloak>{{ datestamp }}</h2>

						</div>
                </div>
                <div class="container">

                        <table class="table">
                        <tbody>
                                <tr v-for="list, index in array">
                                        <td :class="{strikeout : list.crossed}" v-html="list.label" v-cloak><p>{{ list.label }}</p></td>

										<td v-show="list.crossed == 1" style="width:45px; height:52px" class="text-right" v-cloak><i @click="deleteit(index)" class="fa fa-lg fa-times-circle text-danger" aria-hidden="true"></i></td>
										<td v-show="list.crossed == 1" style="width:45px; height:52px" class="text-right" v-cloak><i @click="unstrikeit(index)" class="fa fa-lg fa-undo text-success"  aria-hidden="true"></i></td>
										<td v-show="list.crossed == 0" style="width:45px; height:52px" class="text-right" v-cloak><i @click="strikeit(index)" class="fa fa-lg fa-blank" aria-hidden="true"></i></td>
										<td v-show="list.crossed == 0" style="width:45px; height:52px" class="text-right" v-cloak><i @click="strikeit(index)" class="fa fa-lg fa-minus-circle text-warning" aria-hidden="true"></i></td>



                                </tr>


                        </tbody>
                        </table>

                        <div class="d-flex" style="margin-bottom:20px">
                            <input type="text" ref="item" class="form-control mr-1"   :value="items.label" @input="items.label= $event.target.value" v-on:keyup.enter="process">
                            <span class="input-group-btn">
                                      <button type="submit" @click.prevent="process" class="btn btn-default"><i  class="fa fa-plus" aria-hidden="true"></i></button>
                            </span>

                        </div>

                 </div>
     </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>

		var month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];




        var app = new Vue({
            el: '#app',
            data: {
                    content:"",
                    items:{},
                    array: [],
					action: "",
					name: "",
					crossed: "",
					deleted: "",

					timestamp: "",
					datestamp: "",

            },
			created() {
                setInterval(this.getNow, 1000);
            },
            methods: {
					getNow: function() {
                    const today = new Date();
                    const date = month[(today.getMonth())] +' '+today.getDate()+', '+today.getFullYear();
					ampm = (today.getHours() >= 12) ? "PM" : "AM";
                    const time = today.getHours().toString().replace(/^(\d)$/, '0$1') + ":" + today.getMinutes().toString().replace(/^(\d)$/, '0$1') + ' ' + ampm;
                    this.timestamp = time;
					this.datestamp = date;

					},
					getContacts: function(){
						axios.get('../db.php')
						.then(function (response) {


						console.log(response.data);



						for(var i = 0; i < response.data.length; i++)
						{
								if('http://' == response.data[i].action.substring(0,7))
								{
									var ton = "<a href='" + response.data[i].action + "' target='_blank'>" + response.data[i].action + "</a>";
									app.array.push({ "label":ton, "crossed":(!!parseInt(response.data[i].crossed))});

								}
								else if('https://' == response.data[i].action.substring(0,8))
								{
									var ton = "<a href='" + response.data[i].action + "' target='_blank'>" + response.data[i].action + "</a>";
									app.array.push({ "label":ton, "crossed":(!!parseInt(response.data[i].crossed))});

								}
								else{
									app.array.push({ "label":(response.data[i].action), "crossed":(!!parseInt(response.data[i].crossed))});
									//console.log(response.data);
								}
							}

						})
						.catch(function (error) {
							console.log(error);
						});
					},

                    process: function()
                    {
                        items = { label:'', crossed: 0};

							items.label = this.items.label.charAt(0).toUpperCase() + this.items.label.slice(1);
							if('Http'==items.label.substring(0,4)){items.label = this.items.label.charAt(0).toLowerCase() + this.items.label.slice(1); ;} //remove capital for links


								items.label = items.label.replace(/[^A-Za-z0-9/\=:?,\.\-\_\'\"]/g, ' ');
								this.array.push(items);
								action = app.array[app.array.length-1].label;
								this.items.label = "";




                        this.$refs.item.focus();
                        setTimeout( function(){ window.scrollTo(0, document.body.scrollHeight)}, 100);

						/*-------------------------AXIOS-----------------------------*/
						console.log("create action")

						let formData = new FormData();
						console.log("action:", action)
						formData.append('action', action)

						axios({
							method: 'post',
							url: '../db.php',
							data: formData,
							config: { headers: {'Content-Type': 'multipart/form-data' }}
						})
						.then(function (response) {
							//handle success
							console.log(response)


						})
						.catch(function (response) {
							//handle error
							console.log(response)
						});




                    },


					deleteit(index)
					{
						name = this.array[index].label;
						if(this.array[index].crossed == true)
                            {

								/*----delete function ---*/
								this.$delete(this.array, index);
								this.$refs.item.focus();
								deleted = 1;
								const newformdata = new FormData();
								newformdata.append('name', name);
								newformdata.append('deleted', deleted);

								axios({

								method:'post',

								url:'../db.php',
								data:newformdata,
								config: { headers: {'Content-Type': 'multipart/form-data' }}
								})
								.then(function (response) {
									//handle success
									console.log(response)


								})
								.catch(function (response) {
									//handle error
									console.log(response)
								});


                            }
					},
					unstrikeit(index)
                    {
								name = this.array[index].label;

								if(this.array[index].crossed == true)
								{
                                this.array[index].crossed = false;

								console.log(this.array[index].label);
								/*----strike function ---*/
								const newformdata = new FormData();
								crossed = 0;

								if("<a href='" == name.substring(0,9) )

								{

									name = name.replace(/<a href='/, "");
									name = name.substring(0,name.indexOf("'"))
									console.log(name);
								}

								newformdata.append('crossed', crossed);
								newformdata.append('name', name);
								axios({

								method:'post',

								url:'../db.php',
								data:newformdata,
								config: { headers: {'Content-Type': 'multipart/form-data' }}
								})
								.then(function (response) {
									//handle success
									console.log(response)


								})
								.catch(function (response) {
									//handle error
									console.log(response)
								});


                              }




        },

        strikeit(index)
        {
								name = this.array[index].label;

								if(this.array[index].crossed == false)
								{
                                this.array[index].crossed = true;

								console.log(this.array[index].label);
								/*----strike function ---*/
								const newformdata = new FormData();
								crossed = 1;

								if("<a href='" == name.substring(0,9) )

								{

									name = name.replace(/<a href='/, "");
									name = name.substring(0,name.indexOf("'"))
									console.log(name);
								}

								newformdata.append('crossed', crossed);
								newformdata.append('name', name);
								axios({

								method:'post',

								url:'../db.php',
								data:newformdata,
								config: { headers: {'Content-Type': 'multipart/form-data' }}
								})
								.then(function (response) {
									//handle success
									console.log(response)


								})
								.catch(function (response) {
									//handle error
									console.log(response)
								});


                              }




                    }

               }

        });

		app.getContacts();

    </script>
  </body>
</html>
