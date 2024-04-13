<template>
	<div class="c-pageSet__navi--valError">
		<div class="c-pageSet__catch--alert"><i class="fa-solid fa-circle-exclamation"></i>
			<span class="c-pageSet__catchText">入力内容を確認してください</span>
		</div>
		<ul class="c-pageSet__errorList">
			<li v-for="(error, index) in errors" v-if="error.length > 0" 
				:key="index" class="c-pageSet__errorItem">・{{ error[0] }}
			</li>
		</ul>
	</div>
</template>

<script>
export default {
	props: [
		'errors',
	],

	data(){
		return {
		}
	},
}
</script>
