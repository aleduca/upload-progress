<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload with progress bar</title>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Kode+Mono:wght@400..700&display=swap');

    body {
      font-family: "Kode Mono";
    }
  </style>
</head>

<body>
  <div class="container mx-auto">
    <form @submit.prevent="upload" x-data="{
      file:null,
      error:'',
      message:'',
      progress:0,
      upload(){
        this.error = '';
        this.message = '';
        this.progress = 0;

        this.file = this.$refs.file?.files[0];
        console.log(this.file);

        // verificar se escolheu um arquivo
        if(!this.file){
          this.error = '🔴Escolha um arquivo';
          return;
        }

        // verificar a extensao
        const allowedExtensions = ['png','jpeg','jpg','sql','pdf'];
        const extension = this.file.name.split('.').pop().toLowerCase();
        if(!allowedExtensions.includes(extension)){
          this.error = '🔴Extensão não aceita';
          return;
        }

        // verificar tamanho
        if(this.file.size > 40 * 1024 * 1024){
          this.error = '🔴Arquivo muito grande';
          return;
        }


        // pegar dados com formdata
        const formdata = new FormData();
        formdata.append('file', this.file);

        // xmlhttprequest
        const xhr = new XMLHttpRequest();
        xhr.open('POST','upload.php',true);

        // progresso
        xhr.upload.onprogress = (event) => {
          if(event.lengthComputable){
            this.progress = Math.round((event.loaded / event.total) * 100);
          }
        }

        // pegar resposta do server
        xhr.onload = () => {
          if(xhr.status === 200){
            this.message = xhr.responseText;
            this.error = '';
          }else{
            this.error = xhr.responseText;
            this.progress = 0;
            this.message = '';
          }
        }

        // erro no envio
        xhr.onerror = () => {
          this.error = 'Erro duranto o upload';
        }

        // send
        xhr.send(formdata);
      }

    }">
      <div class="space-y-12">
        <div class="pb-6">
          <h2 class="text-2xl font-semibold text-gray-900">Upload File</h2>

          <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <div class="col-span-full">
              <label for="cover-photo" class="block text-sm/6 font-medium text-gray-900">Upload new File</label>

              <template x-if="progress > 0">
                <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700">
                  <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" :style="`width: ${progress}%`" x-text="`${progress}%`"></div>
                </div>
              </template>

              <template x-if="error">
                <div x-text="error" class="text-red-600 italic text-xs"></div>
              </template>
              <template x-if="message">
                <div x-text="message" class="text-green-600 italic text-xs"></div>
              </template>
              <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">

                <div class="text-center">
                  <svg class="mx-auto size-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon">
                    <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z" clip-rule="evenodd" />
                  </svg>
                  <div class="mt-4 flex text-sm/6 text-gray-600">
                    <label for="file-upload" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 focus-within:outline-hidden hover:text-indigo-500">
                      <span>Upload a file</span>
                      <input id="file-upload" x-ref="file" name="file-upload" type="file" />
                    </label>
                  </div>
                  <p class=" text-xs/5 text-gray-600">PNG, JPG, GIF up to 40MB</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <button type="submit" class="bg-green-700 rounded p-2 text-xs text-white cursor-pointer">Upload</button>
    </form>

  </div>
</body>

</html>