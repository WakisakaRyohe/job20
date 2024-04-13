<template>
	<div class="c-accordion" :class="{'p-topFaq__accordion': topFlg}">
		<a class="c-accordion__toggle" :class="{'p-topFaq__toggle': topFlg}" @click="toggle()">
			<slot name="title"></slot>
			<transition name="rotation">
				<i v-if="!isOpen" class="fas fa-chevron-down c-accordion__icon" 
				:class="[ {'p-searchPanel__accordion': searchFlg}, {'p-topFaq__icon': topFlg} ]"></i>
			</transition>
			<transition name="rotation">
				<i v-if="isOpen" class="fas fa-chevron-up c-accordion__icon" 
				:class="[ {'p-searchPanel__accordion': searchFlg}, {'p-topFaq__icon': topFlg} ]"></i>
			</transition>
		</a>

		<transition name="accordion" @before-enter="beforeEnter" @enter="enter" @before-leave="beforeLeave" @leave="leave">
			<div class="c-accordion__body" v-if="isOpen">
				<slot name="detail"></slot>
			</div>
		</transition>
	</div>
</template>

<script>
export default {
	props: [
		"commitment",
		"topFlg",
		"searchFlg",
	],
	data(){
		return {
			isOpen: false,
		}
	},
	methods:{
		toggle: function () {
			this.isOpen = !this.isOpen;
        },
        beforeEnter: function (el) {
			el.style.height = '0';
        },
        enter: function (el) {
			el.style.height = el.scrollHeight + 'px';
        },
        beforeLeave: function (el) {
			el.style.height = el.scrollHeight + 'px';
        },
        leave: function (el) {
			el.style.height = '0';
        }
	},
	computed:{
	},
	mounted(){
	},
}
</script>

<style>
.rotation-enter, .rotation-leave-to {
	transform: rotateX(180deg)
}
</style>  
