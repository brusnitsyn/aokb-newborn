<script setup>
import {format, formatDistanceToNow} from "date-fns";
import {ru} from "date-fns/locale/ru";
import {useNow} from "@vueuse/core";
import {computed} from "vue";

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

const now = useNow()
const formattedNow = computed(() => format(now.value, 'dd MMMM yyyy HH:mm:ss', { locale: ru }))

const formatTimeAgo = (date) => {
    return formatDistanceToNow(date, {
        locale: ru,
        addSuffix: true,
        includeSeconds: true
    })
}
</script>

<template>
    <div class="grid grid-cols-2 relative bg-gray-400">
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
                <div class="mb-4 bg-[#ec6608] rounded-full w-[120px] h-[120px] flex items-center justify-center">
                    <span class="text-[80px] font-bold text-[#384653] leading-18">
                      {{ countInDayBoy }}
                    </span>
                </div>
                <div class="flex flex-col gap-y-2 w-[428px]">
                    <div v-for="newborn in latestTheeHistoryBoy" class="rounded-3xl bg-gray-300 border-2 border-gray-500 relative">
                        <div class="flex flex-row items-center min-h-[48px]">
                            <div class="w-[56px] h-[48px]">
                                <div
                                    class="flex items-center justify-center rounded-full text-xl text-[#384653] font-bold bg-[#ec6608] w-[56px] border-2 border-[#384653] absolute -left-1 -top-1 -bottom-1">
                                    {{ newborn.num }}
                                </div>
                            </div>
                            <div class="grid grid-cols-2 ml-[16px] w-full font-bold text-[#384653]">
                                <span class="text-left">{{ newborn.Name }} {{ newborn.OT[0] }}</span>
                                <span class="text-left">{{ formatTimeAgo(newborn.BD) }}</span>
                            </div>
                        </div>
                    </div>
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
                <div class="mb-4 bg-[#ec6608] rounded-full w-[120px] h-[120px] flex items-center justify-center">
                    <span class="text-[80px] font-bold text-[#384653] leading-18">
                      {{ countInDayGirl }}
                    </span>
                </div>
                <div class="flex flex-col gap-y-2 w-[428px]">
                    <div v-for="newborn in latestTheeHistoryGirl" class="rounded-3xl bg-gray-300 border-2 border-gray-500 relative">
                        <div class="flex flex-row items-center min-h-[48px]">
                            <div class="w-[56px] h-[48px]">
                                <div
                                    class="flex items-center justify-center text-xl rounded-full text-[#384653] font-bold bg-[#ec6608] w-[56px] border-2 border-[#384653] absolute -left-1 -top-1 -bottom-1">
                                    {{ newborn.num }}
                                </div>
                            </div>
                            <div class="grid grid-cols-2 ml-[16px] w-full font-bold text-[#384653]">
                                <span class="text-left">{{ newborn.Name }} {{ newborn.OT[0] }}</span>
                                <span class="text-left">{{ formatTimeAgo(newborn.BD) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
