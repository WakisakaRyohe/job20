<template>
	<transition name="flash">
		<div v-if="show" class="c-flashMessage" :class="{error: isError}">{{ message }}</div>
	</transition>
</template>
  
<script>
export default {
	data() {
		return {
			show: false,
			message: null,
			isError: false,
		}
	},
	mounted(){
		const errorMessage = localStorage.getItem(['error_message']);

		// エラーメッセージかフラッシュメッセージを格納
		if(errorMessage){
			this.message = errorMessage;
			this.isError = true;
		}else{
			this.message = localStorage.getItem(['flash_message']);
		}
		
		if(this.message !== null){
			this.show = true;
			
			setTimeout(()=>{
				this.show = false;
				//セッションからメッセージ削除
				localStorage.removeItem(['flash_message']);
				localStorage.removeItem(['error_message']);
			}, 2000)
		}
	}
}
</script>

<style>
    /* 表示から非表示までのアニメーション */
    .flash-leave-active {
      transition: opacity 3s;
    }
    .flash-leave-to
    {
      opacity: 0;
    }
	.error{
		background: rgb(255, 190, 190);
	}
</style>  
