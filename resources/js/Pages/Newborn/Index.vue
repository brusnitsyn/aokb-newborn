<script setup>
import {format, formatDistanceToNowStrict, isDate} from "date-fns";
import {ru} from "date-fns/locale/ru";
import {useNow} from "@vueuse/core";
import {computed, onMounted, ref} from "vue";
import {useMotions} from "@vueuse/motion";

const props = defineProps({
    latestTheeHistoryBoy: {
        type: Array,
    },
    latestTheeHistoryGirl: {
        type: Array,
    },
    countInDayBoy: {
        type: Number,
    },
    countInDayGirl: {
        type: Number,
    },
    countBoy: {
        type: Number,
    },
    countGirl: {
        type: Number,
    },
})

const latestBoys = ref([...props.latestTheeHistoryBoy])
const latestGirls = ref([...props.latestTheeHistoryGirl])
const countDayBoy = ref(props.countInDayBoy)
const countDayGirl = ref(props.countInDayGirl)
const countAllBoys = ref(props.countBoy)
const countAllGirls = ref(props.countGirl)

const showTotalCard = ref(false)
const now = useNow()
const motions = useMotions()
const formattedNow = computed(() => format(now.value, 'dd MMMM yyyy HH:mm:ss', { locale: ru }))

const formatTimeAgo = (date) => {
    return formatDistanceToNowStrict(date, {
        locale: ru,
        addSuffix: true,
        includeSeconds: true
    })
}

onMounted(() => {
    // Запускаем интервал для показа карточки
    setInterval(() => {
        showTotalCard.value = true
        setTimeout(() => {
            showTotalCard.value = false
        }, 10000) // 10 секунд
    }, 300000) // 5 минут

    window.Echo.channel(`aokb.newborn.finally`)
        .listen('.aokb.newborn.finally', (data) => {
            latestBoys.value = data.latestTheeHistoryBoy
            latestGirls.value = data.latestTheeHistoryGirl
            countDayBoy.value = data.countInDayBoy
            countDayGirl.value = data.countInDayGirl
            countAllBoys.value = data.countBoy
            countAllGirls.value = data.countGirl
        })
})
</script>

<template>
    <div class="grid grid-cols-2 relative bg-gray-400 max-h-screen overflow-hidden">
        <div class="h-[63px] w-[278px] bg-contain absolute top-6 left-8"
             style="background-image: url(/assets/img/logo-full.svg);"></div>
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2">
            <div class="rounded-full px-6 p-3 bg-gray-200 border-2 border-gray-500 font-bold text-[#384653] text-2xl">
                {{ formattedNow }}
            </div>
        </div>
        <div class="h-screen flex flex-col items-center align-center justify-center border-r-2 border-gray-400"
             style="background-image: url(/assets/img/boy-background.svg);">
            <div class="flex flex-col items-center justify-center text-center w-full">
                <div class="h-[350px] w-[420px] bg-contain" style="background-image: url(/assets/img/boy.svg);"></div>
                    <span class="text-[40px] font-bold text-[#384653]">
                      МАЛЬЧИКИ
                    </span>
                <div class="flex flex-col items-center relative">
                    <div class="mb-4 bg-[#ec6608] rounded-full w-[120px] h-[120px] flex items-center justify-center border-2 border-[#384653]">
                        <span class="text-[80px] font-bold text-[#384653] leading-18">
                          {{ countDayBoy }}
                        </span>
                    </div>
                    <div class="flex flex-col gap-y-2 w-[428px] h-[172px]">
                        <div v-for="newborn in latestBoys" class="rounded-3xl bg-gray-300 border-2 border-gray-500 relative">
                            <div class="flex flex-row items-center min-h-[48px]">
                                <div class="w-[56px] h-[48px]">
                                    <div
                                        class="flex items-center justify-center rounded-full text-xl text-[#384653] font-bold bg-[#ec6608] w-[56px] border-2 border-[#384653] absolute -left-1 -top-1 -bottom-1">
                                        {{ newborn.num }}
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 ml-[16px] w-full font-bold text-[#384653]">
                                    <span class="text-left">{{ newborn.Name }} {{ newborn.FAMILY ? newborn.FAMILY[0] : '' }}</span>
                                    <span class="text-left">{{ formatTimeAgo(newborn.BD) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <transition :css="false"
                                @leave="(el, done) => motions.cardBoys.leave(done)">
                        <div v-if="showTotalCard"
                             v-motion="'cardBoys'"
                             :initial="{
                                y: 500,
                             }"
                             :enter="{
                                y: 0,
                             }"
                             :leave="{
                                 y: 500
                             }"
                             class="absolute bg-[#ec6608] top-0 -bottom-1 -inset-x-1 flex flex-col justify-center items-center rounded-3xl shadow">
                            <div class="bg-[#ec6608] rounded-full px-16 h-[120px] flex items-center justify-center">
                                <span class="text-[80px] font-bold text-white leading-18">
                                  {{ countAllBoys }}
                                </span>
                            </div>
                            <span class="text-2xl font-bold text-white">
                                за {{ now.getFullYear() }} год
                            </span>
                        </div>
                    </transition>
                </div>
            </div>
        </div>
        <div class="h-screen flex flex-col items-center align-center justify-center border-l-2 border-gray-500"
             style="background-image: url(/assets/img/girl-background.svg);">
            <div class="flex flex-col items-center justify-center text-center w-full">
                <div class="h-[350px] w-[420px] bg-contain" style="background-image: url(/assets/img/girl.svg);"></div>
                    <span class="text-[40px] border-[#ec6608] font-bold text-[#384653]">
                      ДЕВОЧКИ
                    </span>
                <div class="flex flex-col items-center relative">
                    <div class="mb-4 bg-[#ec6608] rounded-full w-[120px] h-[120px] flex items-center justify-center border-2 border-[#384653]">
                        <span class="text-[80px] font-bold text-[#384653] leading-18 ">
                          {{ countDayGirl }}
                        </span>
                    </div>
                    <div class="flex flex-col gap-y-2 w-[428px] h-[172px]">
                        <div v-for="newborn in latestGirls" class="rounded-3xl bg-gray-300 border-2 border-gray-500 relative">
                            <div class="flex flex-row items-center min-h-[48px]">
                                <div class="w-[56px] h-[48px]">
                                    <div
                                        class="flex items-center justify-center text-xl rounded-full text-[#384653] font-bold bg-[#ec6608] w-[56px] border-2 border-[#384653] absolute -left-1 -top-1 -bottom-1">
                                        {{ newborn.num }}
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 ml-[16px] w-full font-bold text-[#384653]">
                                    <span class="text-left">{{ newborn.Name }} {{ newborn.FAMILY ? newborn.FAMILY[0] : '' }}</span>
                                    <span class="text-left">{{ formatTimeAgo(newborn.BD) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <transition :css="false"
                                @leave="(el, done) => motions.cardGirl.leave(done)">
                        <div v-if="showTotalCard"
                             v-motion="'cardGirl'"
                             :initial="{
                                y: 500,
                             }"
                             :enter="{
                                y: 0,
                             }"
                             :leave="{
                                 y: 500
                             }"
                             class="absolute bg-[#ec6608] top-0 -bottom-1 -inset-x-1 flex flex-col justify-center items-center rounded-3xl shadow">
                            <div class="bg-[#ec6608] rounded-full px-16 h-[120px] flex items-center justify-center">
                                <span class="text-[80px] font-bold text-white leading-18">
                                  {{ countAllGirls }}
                                </span>
                            </div>
                            <span class="text-2xl font-bold text-white">
                                за {{ now.getFullYear() }} год
                            </span>
                        </div>
                    </transition>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
