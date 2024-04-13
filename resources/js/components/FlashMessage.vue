<template>
	<transition name="flash">
		<Modal v-show="isShowFlash">
			<template slot="body">
				<div class="c-modal__flash" v-html="flashMessage"></div>
			</template>
		</Modal>
	</transition>
</template>

<script>
import Modal from './Modal.vue';

export default {
	components: {
		"Modal": Modal,
	},

	computed:{
		// モーダル表示判定フラグ
		isShowFlash: function () {
			const message = localStorage.getItem(['flash_message']);

			// モーダル表示
			if(message){
				this.$store.commit('Modal/setFlash', true);
			}

			return this.$store.state.Modal.modal.flash.isShow;
		},

		flashMessage: function () {
			const message = localStorage.getItem(['flash_message']);

			if(message){
				//ローカルストレージのメッセージをvuexに保存
				this.$store.commit('Modal/setMessage', message);
				//ローカルストレージからメッセージ削除
				localStorage.removeItem(['flash_message']);
			}

			return this.$store.state.Modal.modal.flash.message;
		},
	},
}
</script>
