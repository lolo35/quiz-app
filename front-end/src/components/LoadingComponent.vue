<template>
    <div class="absolute top-0 bottom-0 left-0 right-0 z-10 bg-black opacity-30">
        
    </div>
    <div class="absolute top-0 bottom-0 left-0 right-0 z-20">
        <div class="flex flex-col w-full h-full items-center justify-center">
            <span class="loader"></span>
        </div>
    </div>
</template>
<style>
.loader {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  display: inline-block;
  border-top: 4px solid #FFF;
  border-right: 4px solid transparent;
  box-sizing: border-box;
  animation: rotation 1s linear infinite;
}
.loader::after {
  content: '';  
  box-sizing: border-box;
  position: absolute;
  left: 0;
  top: 0;
  width: 48px;
  height: 48px;
  border-radius: 50%;
  border-left: 4px solid #0078D7;
  border-bottom: 4px solid transparent;
  animation: rotation 0.5s linear infinite reverse;
}
@keyframes rotation {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
} 
</style>