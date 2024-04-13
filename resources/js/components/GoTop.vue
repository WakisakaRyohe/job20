<template>
	<div v-if="scrollY > 100" class="c-goTop" :style="{ bottom: position }" @click="goTop">
		<i class="fas fa-chevron-up c-goTop__icon"></i>
	</div>
</template>

<script>
export default {
	data(){
		return {
			scrollY: 0,
		}
	},

	methods:{
		// スクロール量取得
		getScroll() {
			this.scrollY = window.scrollY
		},
		// トップに戻る
		goTop(){
			window.scrollTo( { top: 0, behavior: 'smooth' } );
		},
	},

	computed: {
		// bottomの位置変更
		position() {
			return String(this.$store.state.GoTop.bottom) + "px";
		},
	},

	mounted() {
		window.addEventListener("scroll", this.getScroll);
	},

	beforeDestroy: function () {
		window.removeEventListener("scroll", this.getScroll);
	},
}
</script>
