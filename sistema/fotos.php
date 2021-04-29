<section role="main" class="content-body">
   <header class="page-header">
      <h2>Upload de fotos</h2>
      <div class="right-wrapper pull-right" style='margin-right:15px;'></div>
   </header>

   <section class="panel box_shadow">
      <div class="panel-body">
         <div class="row">
            <div class="col-md-12">
               Upload de fotos HTML5 + Canvas<br>
               <button type="button" class="btn btn-success" id="bt_foto"><i class="fa fa-camera"></i><sup><i class="fa fa-plus"></i></sup> Foto</button>
               <hr>

               <input type="file" id="imageInput" accept = "image/*" class="hidden">
               <canvas id= "myCanvas" width="400" height="200" style="background-color:#FFFFF0"></canvas>
               <script>
                 var maxWidth  = 400;
	              var maxHeight = 200;
                 let imgInput  = document.getElementById('imageInput');

                 imgInput.addEventListener('change', function(e) {

                   if(e.target.files)
                   {
                     let imageFile = e.target.files[0]; //here we get the image file
                     var reader    = new FileReader();
                     reader.readAsDataURL(imageFile);

                     reader.onloadend = function (e)
                     {
                       var myImage      = new Image();     // Creates image object
                       myImage.src      = e.target.result; // Assigns converted image to image object
                       var width        = myImage.width;
                       var height       = myImage.height;

                       if(width == 0 || height == 0){width = 800; height = 600; console.log('INFOS DA IMAGEM nao lido'); }

                       var shouldResize = (width > maxWidth) || (height > maxHeight);
                       var newWidth;
               		  var newHeight;

                       if(!shouldResize){ newWidth = width; newHeight = height; console.log("SEM RESIZE DE IMG !"); }

               		  if (width > height) {
                  			newHeight = height * (maxWidth / width);
                  			newWidth  = maxWidth;
               		  } else {
                  			newWidth  = width * (maxHeight / height);
                  			newHeight = maxHeight;
               		  }
                       console.log("Img orginal: "+width+" - "+height);
                       console.log("Img NOVA: "+newWidth+" - "+newHeight);
                       myImage.onload = function(ev)
                       {
                         var myCanvas  = document.getElementById("myCanvas"); // Creates a canvas object
                         var myContext = myCanvas.getContext("2d"); // Creates a contect object

                         myCanvas.width  = newWidth;
		                   myCanvas.height = newHeight;
                         //myCanvas.width  = 400;
		                   //myCanvas.height = 400;
                         //myCanvas.width = myImage.width; // Assigns image's width to canvas
                         //myCanvas.height = myImage.height; // Assigns image's height to canvas
                         myContext.drawImage(myImage,0,0,newWidth, newHeight); // Draws the image on canvas
                         //myContext.drawImage(myImage,0,0,200,200); // Draws the image on canvas

                         let imgData = myCanvas.toDataURL("image/jpeg",0.75); // Assigns image base64 string in jpeg format to a variable

                         sendFile(imgData);
                         //console.log(myCanvas);

                       }
                     }
                   }
                 });

                 function sendFile(fileData) {
                  	var formData = new FormData();
                  	formData.append('img', fileData);

                  	$.ajax({
                  		type: 'POST',
                  		url: 'sistema/fotos_upload.php',
                        dataType: 'json',
                  		data: formData,
                  		contentType: false,
                  		processData: false,
                  		success: function (data){
                           console.log(data);
                           if (data.success) {
                  				alert('Your file was successfully uploaded!');
                  			} else {
                  				alert('There was an error uploading your file!');
                  			}
                  		},
                  		error: function (data) {
                  			alert('There was an error uploading your file!!!!');

                  		}
                  	});
                  }
                 $("#myCanvas").click(function(){ $("#imageInput").click();});
               </script>
            <div>
         </div>
      </div>
   </section>
</section>
