<template>
	<div class="c-pageSet u-mb0">
		<div class="c-pageSet__navi--requireWebResume">
			<div class="c-pageSet__copy u-mt0">
				<i class="fa-solid fa-circle-exclamation c-pageSet__naviIcon"></i>
				<span class="c-pageSet__naviText">
					求人に応募するには、<router-link :to="`/job20/web_resume`" class="c-pageSet__link">こちら</router-link>でWEB履歴書を登録してください。
				</span>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	data(){
		return {
		}
	},
}
</script>
