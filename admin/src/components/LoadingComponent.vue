<template>
    <div class="absolute top-0 bottom-0 left-0 right-0 bg-black opacity-30 z-10"></div>
    <div class="absolute top-0 bottom-0 left-0 right-0 z-20">
        <div class="flex flex-col w-full h-full items-center justify-center">
            <span class="loader">

            </span>
        </div>
    </div>
</template>
<style scoped>
.loader {
  box-sizing: border-box;
  position: relative;
  width: 48px;
  height: 48px;
  animation: spin 1s linear infinite;
}
.loader:after, .loader:before {
  content: "";
  width: 24px;
  height: 24px;
  position: absolute;
  border-radius: 50%;
  background: #FF3D00;
  animation: spin 1s linear infinite;
  transform-origin: 0px 100%;
}
.loader:before {
  transform-origin: 0 50%;
  background: #fff;
}
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
    
</style>