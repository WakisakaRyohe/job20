<template>
	<div class="c-modal">
		<div class="c-modal__bg" @click.self="close">
			<slot name="body"></slot>
		</div>
	</div>
</template>

<script>
export default {
	data(){
		return {
		}
	},

	methods:{
		close: function () {
			this.$store.commit('Modal/close', { modalName : "apply" });
			this.$store.commit('Modal/close', { modalName : "BookmarkJobs" });
			this.$store.commit('Modal/close', { modalName : "certification" });
			this.$store.commit('Modal/close', { modalName : "commitment" });
			this.$store.commit('Modal/close', { modalName : "delete" });
			this.$store.commit('Modal/close', { modalName : "flash" });
			this.$store.commit('Modal/close', { modalName : "jobDetail" });
			this.$store.commit('Modal/close', { modalName : "withdrawal" });
		},
	},

	mounted(){
		let message = this.$store.state.Modal.modal.flash.message;

		if(message !== null){      
			setTimeout(()=>{
				this.$store.commit('Modal/setFlash', false);
			}, 2000);

			//セッションからメッセージ削除
			setTimeout(()=>{
				this.$store.commit('Modal/setMessage', null);
			}, 5000)
		}
	},
}
</script>

<style>
/* 表示から非表示までのアニメーション */
.modal-enter-active, .modal-leave-active{
  transition: opacity .3s;
}
.modal-enter{
  opacity: 0;
}
.modal-enter-to{
  opacity: 1;
}
.modal-leave{
  opacity: 1;
}
.modal-leave-to{
  opacity: 0;
}

/* フラッシュメッセージ表示から非表示までのアニメーション */
.flash-leave-active {
  transition: opacity 3s;
}
.flash-leave-to
{
  opacity: 0;
}

/* 求人詳細のLightBoxのアニメーション */
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s ease;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>  
