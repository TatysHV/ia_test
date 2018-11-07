$(document).ready(function(){
	$('.modal').modal();//Funcionamiento de ventanas modales Materiallize

	//Trae los datos de la API, que son arrojados en formato JSON
	$.getJSON( "http://appslim/api/peliculas", function( data ) { 

	var divPeli = ""; //Variable que permitirá concatenar los resultados del JSON

		$.each( data, function( key, pelicula ) { // key, value Se accede a cada valor del JSON, {"key":"value", "key":"value"}
		
			divPeli += '<div class="col s4">'+ //Concatenación de los resultados obtenidos con cada vuelta del ciclo each
			'<div class="card horizontal">'+
				'<div class="card-image" style="width: 180px; heigth: auto">'+
					'<img src="images/'+pelicula.poster+'" style="padding: 10px;">'+
				'</div>'+
				'<div class="card-stacked">'+
					'<div class="card-content">'+
						'<div class="col s12">'+
						'<center><h5>'+pelicula.titulo+'</h5>'+
							'<h6 id="stock" style="color:#00838F"><b><span>'+pelicula.fecha+'</span> </b></h6>'+
								'<a class="waves-effect waves-light btn" onclick="showPeli('+pelicula.id+')">Ver contenido</a>'+
						'</center>'+
						'</div>'+
					'</div>'+
					'</div>'+
			'</div>'+
			'</div>';
		});

		$(divPeli).appendTo("#divPelis"); //Se coloca la cadena concatenada con todos los resultados de la consulta en la div #divPelis
		
	});

});


function addPelicula(){
	//Creación de nuevo formData que permite enviar todo el formulario de registro de manera empaquetada
	var formData = new FormData(document.getElementById("frm_pelicula")); 


	//Uso de ajax para enviar formulario hacia archivo PHP
	$.ajax({
		url: "../api/peliculas/agregar",
		data: formData, //Envío del formulario completo al PHP para poder subir imagen mediante PHP
		type: "post",
		contentType: false,
		processData: false,
		 success: function(data){
			//alert("Pelicula registrada"+data);
			window.location.replace("http://appslim/index.html"); //Refresca la página de index para actualizar valores
		 },
		 failure: function(data){
			alert(data);
		 }
	   });
	
}

function showPeli(id_peli){
	var id = id_peli;
		
		//Trae los datos de la API, que son arrojados en formato JSON
		var url = 'http://appslim/api/peliculas/'+id+'';

		$("#principal").html(""); //Limpia el área de la div #principal

		$.getJSON( url, function( data ) { 

			var infoPeli = "";
		
				$.each( data, function( key, pelicula ) { // key, value Se accede a cada valor del JSON, {"key":"value", "key":"value"}
				
					infoPeli = 
					'<center>'+
                    '<img src="images/'+pelicula.poster+'">'+
					'<h4 style="color: #00838F">'+pelicula.titulo+'</h4>'+
					'<h5>Fecha de estreno: '+pelicula.fecha+'</h5>'+
					'<br>'+
					'<h4>Sinopsis</h4>'+
					'<p>'+pelicula.sinopsis+'.</p>'+
					'<h4>Reseña</h4>'+
					'<p>'+pelicula.resena+'.</p>'+
					'</center>';
				});
		
				$(infoPeli).appendTo("#principal"); //Se coloca la cadena concatenada con todos los resultados de la consulta en la div #divPelis
				$('#modal2').modal('open');
			});


}


function showModal2(x){ //función encargada de mostrar la ventana modal que muestra los detalles de la película
	$('#modal2').modal('open');
}


