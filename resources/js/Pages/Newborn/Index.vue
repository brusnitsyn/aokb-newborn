<script setup>
import { format } from "date-fns";
import { ru } from "date-fns/locale/ru";
import { useEventSource, useNow } from "@vueuse/core";
import { computed, onMounted, ref } from "vue";
import { useMotions } from "@vueuse/motion";
import { useDateColors } from "../../Composables/useDateColors.js";
import BoyIllustration from "../../Components/BoyIllustration.vue";
import GirIllustration from "../../Components/GirIllustration.vue";

const props = defineProps({
    latestTheeHistoryBoy: Array,
    latestTheeHistoryGirl: Array,
    countInDayBoy: Number,
    countInDayGirl: Number,
    countBoy: Number,
    countGirl: Number,
})

const latestBoys = ref([...props.latestTheeHistoryBoy])
const latestGirls = ref([...props.latestTheeHistoryGirl])
const countDayBoy = ref(props.countInDayBoy)
const countDayGirl = ref(props.countInDayGirl)
const countAllBoys = ref(props.countBoy)
const countAllGirls = ref(props.countGirl)
const eventSource = ref(null)

const getNowDate = computed(() => {
    if (eventSource.value?.data) {
        const data = JSON.parse(eventSource.value.data)
        return format(new Date(data.time), 'dd MMMM yyyy HH:mm:ss', { locale: ru })
    }
    return format(useNow().value, 'dd MMMM yyyy HH:mm:ss', { locale: ru })
})

const showTotalCard = ref(false)
const now = useNow()
const motions = useMotions()

// Получаем цветовые классы для текущей даты
const colorData = computed(() => {
    const currentDate = eventSource.value?.data
        ? new Date(JSON.parse(eventSource.value.data).time) // JSON.parse(eventSource.value.data).time
        : new Date()

    console.log(currentDate)

    const { allClasses, colorScheme, isSpecialDate } = useDateColors(currentDate)
    const classes = allClasses.value
    const specialDate = isSpecialDate.value
    const scheme = colorScheme.value
    return { colorClasses: classes, colorScheme: scheme, isSpecialDate: specialDate }
})

onMounted(() => {
    eventSource.value = useEventSource(`http://${import.meta.env.VITE_SSE_TIME_SERVICE_URL}/sse/time`)

    setInterval(() => {
        showTotalCard.value = true
        setTimeout(() => {
            showTotalCard.value = false
        }, 10000)
    }, 300000)

    window.Echo.channel(`aokb.newborn.finally`)
        .listen('.aokb.newborn.finally', (data) => {
            console.log(data)
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
    <div class="grid grid-cols-2 relative max-h-screen overflow-hidden"
         :class="colorData?.colorClasses?.background">

        <!-- Логотип -->
        <div class="h-[63px] w-[278px] bg-contain absolute top-6 left-8"
             style="background-image: url(/assets/img/logo-full.svg);"></div>

        <!-- Заголовок -->
        <div class="absolute top-8 left-1/2 -translate-x-1/2">
            <div class="flex flex-col gap-y-2">
                <div class="rounded-full px-6 p-3 bg-gray-200 border-2 font-bold text-2xl uppercase"
                     :class="[colorData?.colorClasses?.textCenter, colorData?.colorClasses?.border]">
                    Новая жизнь начинается здесь
                </div>
            </div>
        </div>

        <!-- Логотип основной -->
        <img class="h-[600px] absolute top-2/3 -translate-1/2 left-1/2"
             src="/assets/img/logo.svg" />

        <!-- Центральная дата -->
        <div class="absolute top-1/3 -translate-y-1/2 left-1/2 -translate-x-1/2">
            <div class="flex flex-col gap-y-2">
                <div class="rounded-full px-6 p-3 bg-gray-200 border-2 font-bold text-2xl uppercase w-fit mx-auto"
                     :class="[colorData?.colorClasses?.textCenter, colorData?.colorClasses?.border]">
                    сегодня
                </div>
                <div v-if="colorData?.isSpecialDate && colorData?.colorScheme.name === 'nedonosh'"
                     class="rounded-full px-6 p-3 bg-gray-200 border-2 font-bold text-2xl uppercase w-fit mx-auto leading-7 text-center"
                     :class="[colorData?.colorClasses?.textCenter, colorData?.colorClasses?.border]">
                    Всемирный день<br>недоношенного ребенка
                </div>
                <div class="rounded-full px-6 p-3 bg-gray-200 border-2 font-bold text-2xl text-center"
                     :class="[colorData?.colorClasses?.textCenter, colorData?.colorClasses?.border]">
                    {{ getNowDate }}
                </div>
            </div>
        </div>

        <!-- Левая колонка - Мальчики -->
        <div class="h-screen flex flex-col items-center align-center justify-center border-r-1"
             :class="colorData?.colorClasses?.border"
             style="background-image: url(/assets/img/boy-background.svg);">
            <div class="flex flex-col items-center justify-center text-center w-full">
                <BoyIllustration :season="colorData?.colorScheme.name" />

                <span class="text-[40px] font-bold" :class="colorData?.colorClasses?.textTitle">
                    МАЛЬЧИКИ
                </span>

                <div class="flex flex-col items-center relative">
                    <!-- Круг с количеством -->
                    <div
                        class="mb-4 rounded-full w-[120px] h-[120px] flex items-center justify-center border-2 border-[#384653]"
                        :class="[colorData?.colorClasses?.accent, colorData?.isSpecialDate ? colorData?.colorClasses.border : 'border-[#384653]']">
                        <span class="text-[80px] font-bold leading-18" :class="colorData?.isSpecialDate ? colorData?.colorClasses.text : 'text-[#384653]'">
                            {{ countDayBoy }}
                        </span>
                    </div>

                    <!-- Список новорожденных -->
                    <div class="flex flex-col gap-y-2 w-[428px] h-[172px]">
                        <div v-for="newborn in latestBoys"
                             class="rounded-3xl border-2 relative"
                             :class="[colorData?.colorClasses?.border, colorData?.isSpecialDate ? 'bg-white' : 'bg-gray-300']">
                            <div class="flex flex-row items-center min-h-[48px]">
                                <div class="w-[56px] h-[48px]">
                                    <div
                                        class="flex items-center justify-center text-xl rounded-full font-bold w-[56px] border-2 absolute -left-1 -top-1 -bottom-1"
                                        :class="[colorData?.colorClasses?.accent, colorData?.isSpecialDate ? colorData?.colorClasses.text : 'text-[#384653]']">
                                        {{ newborn.num }}
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 ml-[16px] w-full font-bold"
                                     :class="colorData?.colorClasses?.text">
                                    <span class="text-left">{{
                                            newborn.Name
                                        }} {{ newborn.FAMILY ? newborn.FAMILY[0] : '' }}</span>
                                    <span class="text-left">{{ newborn.date }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Карточка общего количества -->
                    <transition :css="false"
                                @leave="(el, done) => motions.cardBoys.leave(done)">
                        <div v-if="showTotalCard"
                             v-motion="'cardBoys'"
                             :initial="{ y: 500 }"
                             :enter="{ y: 0 }"
                             :leave="{ y: 500 }"
                             class="absolute top-0 -bottom-1 -inset-x-1 flex flex-col justify-center items-center rounded-3xl shadow"
                             :class="colorData?.colorClasses?.accent">
                            <div class="rounded-full px-16 h-[120px] flex items-center justify-center"
                                 :class="colorData?.colorClasses?.accent">
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

        <!-- Правая колонка - Девочки -->
        <div class="h-screen flex flex-col items-center align-center justify-center border-l-1"
             :class="colorData?.colorClasses?.border"
             style="background-image: url(/assets/img/girl-background.svg);">
            <div class="flex flex-col items-center justify-center text-center w-full">
                <GirIllustration :season="colorData?.colorScheme.name" />

                <span class="text-[40px] font-bold" :class="colorData?.colorClasses?.textTitle">
                    ДЕВОЧКИ
                </span>

                <div class="flex flex-col items-center relative">
                    <!-- Круг с количеством -->
                    <div
                        class="mb-4 rounded-full w-[120px] h-[120px] flex items-center justify-center border-2"
                        :class="[colorData?.colorClasses?.accent, colorData?.isSpecialDate ? colorData?.colorClasses.text : 'border-[#384653]']">
                        <span class="text-[80px] font-bold leading-18" :class="colorData?.isSpecialDate ? colorData?.colorClasses.text : 'text-[#384653]'">
                            {{ countDayGirl }}
                        </span>
                    </div>

                    <!-- Список новорожденных -->
                    <div class="flex flex-col gap-y-2 w-[428px] h-[172px]">
                        <div v-for="newborn in latestGirls"
                             class="rounded-3xl border-2 relative"
                             :class="[colorData?.colorClasses?.border, colorData?.isSpecialDate ? 'bg-white' : 'bg-gray-300']">
                            <div class="flex flex-row items-center min-h-[48px]">
                                <div class="w-[56px] h-[48px]">
                                    <div
                                        class="flex items-center justify-center text-xl rounded-full font-bold w-[56px] border-2 absolute -left-1 -top-1 -bottom-1"
                                        :class="[colorData?.colorClasses?.accent, colorData?.isSpecialDate ? colorData?.colorClasses.text : 'text-[#384653]']">
                                        {{ newborn.num }}
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 ml-[16px] w-full font-bold"
                                     :class="colorData?.colorClasses?.text">
                                    <span class="text-left">{{
                                            newborn.Name
                                        }} {{ newborn.FAMILY ? newborn.FAMILY[0] : '' }}</span>
                                    <span class="text-left">{{ newborn.date }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Карточка общего количества -->
                    <transition :css="false"
                                @leave="(el, done) => motions.cardGirl.leave(done)">
                        <div v-if="showTotalCard"
                             v-motion="'cardGirl'"
                             :initial="{ y: 500 }"
                             :enter="{ y: 0 }"
                             :leave="{ y: 500 }"
                             class="absolute top-0 -bottom-1 -inset-x-1 flex flex-col justify-center items-center rounded-3xl shadow"
                             :class="colorData?.colorClasses?.accent">
                            <div class="rounded-full px-16 h-[120px] flex items-center justify-center"
                                 :class="colorData?.colorClasses?.accent">
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

        <div v-if="colorData?.isSpecialDate && colorData?.colorScheme.name === 'nedonosh'" class="absolute left-4 bottom-4 right-4 text-center leading-none">
            <span class="text-[84px] font-zubilo" :class="colorData?.colorClasses.text">#МНЕНЕФИОЛЕТОВО</span>
        </div>
    </div>
</template>
