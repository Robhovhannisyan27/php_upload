<!DOCTYPE html>
<html>
<head>
	<title>test</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
	<form method="POST" >
		<input type="file" name='file' id="files" accept=".csv,application/vnd.ms-excel" />
		<div class="progress col-xs-6" style="margin: 20px; padding: 0;">
  			<div class="progress-bar progress-bar-striped active" role="progressbar"   
  				aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
    			0%
  			</div>
		</div>
	</form>
	<script>
		let buffer_size = 1024;
		let chunk = 0;
		let reader_offset = 0;
		let pending_content = '';

		function dataURLtoFile(dataurl, filename) {
		    var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
		        bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
		    while(n--){
		        u8arr[n] = bstr.charCodeAt(n);
		    }
		    return new File([u8arr], filename, {type:mime});
		}
		function readAndSendChunk()
		{
			let file = dataURLtoFile(localStorage.getItem('file'), 'hello.csv');
			var reader = new FileReader();
			reader.onloadend = function(evt){
				//check for end of file
				if(evt.loaded == 0) return;
				
				//increse offset value
				reader_offset += evt.loaded;
				
				//check for only complete line
				var last_line_end = evt.target.result.lastIndexOf('\n');
				var content = pending_content + evt.target.result.substring(0, last_line_end);
				pending_content = evt.target.result.substring(last_line_end+1, evt.target.result.length);
				chunk++;
				//upload data
				send(content, chunk);
			};
			var blob;
			if(localStorage.getItem('chunk')) {
				if(localStorage.getItem('chunk') * buffer_size < file.size) {
					blob = file.slice(chunk * buffer_size, (chunk+1) * buffer_size);
					reader.readAsText(blob);
				} else {
					return;

				}
			} else {
				if(reader_offset < file.size) {
					blob = file.slice(reader_offset, reader_offset + buffer_size);
					reader.readAsText(blob);
				} else {
					return;
				}
			}
			
		}
		
		/**
		* Send data to server using AJAX
		*/
		function send(data_chunk, chunk)
		{
			let percentage = (chunk / chunks_lenght) * 100;
			$('.progress > div').attr('aria-valuenow', percentage);	
			$('.progress > div').css('width', `${percentage}%`);	
			$('.progress > div').text(`${percentage}%`);
			$.ajax({
				url: "upload.php",
				method: 'POST',
				data: {data: data_chunk, chunk: chunk , chunks_lenght: chunks_lenght}
			}).done(function(response) {
				readAndSendChunk();
			});
		}

		if(localStorage.getItem('file')) {
			let file = dataURLtoFile(localStorage.getItem('file'), 'hello.csv');
			$.ajax({
				url: "chunk.php",
				method: 'GET'
			}).done(function(response) {
				if(response) {
					chunk = JSON.parse(response)[0];
					chunks_lenght = JSON.parse(response)[1];
					localStorage.setItem('chunk', chunk);
					let percentage = (chunk / chunks_lenght) * 100;
					if(chunks_lenght == chunk) {
						percentage = 0;
					}
					$('.progress > div').attr('aria-valuenow', percentage);	
					$('.progress > div').css('width', `${percentage}%`);	
					$('.progress > div').text(`${percentage}%`);
					readAndSendChunk();
				}
			});
		}
			
		$('input').on('change', function () {
			localStorage.clear();
			let file = this.files[0];
			reader_offset = 0;		//current reader offset position
			pending_content = '';
			chunk = 0;
			chunks_lenght = Math.ceil(file.size / buffer_size);
		   	var reader = new FileReader();
		   	reader.readAsDataURL(file);
		   	reader.onload = function () {
				localStorage.setItem('file', reader.result);
				send();
		   	};
		   	reader.onerror = function (error) {
		     	console.log('Error: ', error);
		   	};
		})
	</script>
</body>
</html>