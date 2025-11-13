// composables/useDateColors.js
import { computed } from 'vue'

export function useDateColors(date = new Date()) {
    const colorScheme = computed(() => {
        const month = date.getMonth()
        const day = date.getDate()
        const minutes = date.getMinutes()
        const season = getSeason(day, month, minutes)

        // Если сезон не активен, возвращаем default стили
        if (!season) {
            return getDefaultScheme()
        }

        return getSeasonScheme(season)
    })

    const isSpecialDate = computed(() => {
        return colorScheme.value.name !== 'default'
    })

    function getSeason(day, month, minutes) {
        // 17 ноября (месяц 10, т.к. январь = 0)
        if (day === 17 && month === 10) return 'nedonosh'

        // Можно добавить другие особые даты
        // if (day === 1 && month === 0) return 'new-year'
        // if (day === 8 && month === 2) return 'womens-day'

        return null
    }

    function getDefaultScheme() {
        return {
            name: 'default',
            background: 'bg-gray-400',
            text: {
                center: 'text-aokb-1',
                default: 'text-aokb-1',
                title: 'text-aokb-1'
            },
            border: 'border-gray-500',
            accent: 'bg-[#ec6608]'
        }
    }

    function getSeasonScheme(season) {
        const schemes = {
            nedonosh: {
                name: 'nedonosh',
                background: 'bg-fiol-100',
                text: {
                    center: 'text-fiol-900',
                    default: 'text-fiol-900',
                    title: 'text-fiol-900 font-bold'
                },
                border: 'border-fiol-900',
                accent: 'bg-fiol-500'
            },
            // Добавьте другие сезоны здесь
            // 'new-year': { ... },
            // 'womens-day': { ... }
        }

        return schemes[season] || getDefaultScheme()
    }

    // Геттер для удобного доступа ко всем классам
    const allClasses = computed(() => ({
        background: colorScheme.value.background,
        text: colorScheme.value.text.default,
        textCenter: colorScheme.value.text.center,
        textTitle: colorScheme.value.text.title,
        border: colorScheme.value.border,
        accent: colorScheme.value.accent
    }))

    return {
        colorScheme,
        allClasses,
        isSpecialDate
    }
}
