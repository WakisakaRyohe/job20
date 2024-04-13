<template>
	<div class="c-form--borderTopNone">

		<!-- 自己PR登録済みの場合 -->
		<template v-if="self_promotion">
			<h3 class="c-form__title--self_promotion">自己PR</h3>

			<table class="c-table--showWebResume">
				<tbody>
					<tr class="c-table__tr">
						<th class="c-table__th">
							<span class="c-table__thTitle">自己PR</span>
						</th>
						<td class="c-table__td--textarea">
							<template v-if="self_promotion.self_promotion">{{ self_promotion.self_promotion }}</template>
							<template v-else>---</template>
						</td>
					</tr>
					<tr class="c-table__tr">
						<th class="c-table__th">
							<span class="c-table__thTitle">志望動機</span>
						</th>
						<td class="c-table__td--textarea">
							<template v-if="self_promotion.motivation">{{ self_promotion.motivation }}</template>
							<template v-else>---</template>
						</td>
					</tr>
				</tbody>
			</table>
		</template>

		<!-- 自己PR未登録の場合 -->
		<div v-else class="c-noData">
			自己PRは<span class="u-spShow"><br></span>登録されていません。
		</div>

		<!-- ボタンエリア -->
		<div class="c-form__alignCenterArea">
			<router-link class="c-btn--m" :to="{ name: 'edit_self_promotion', params: { propSelfPromotion: self_promotion } }">
				<template v-if="self_promotion">更新</template><template v-else>登録</template>画面へ進む</router-link>
		</div>
	</div>
</template>

<script>
export default {
	props: [
		'self_promotion',
	],
	data(){
		return {
		}
	},
	methods:{
	},
	computed:{
	},
}
</script>