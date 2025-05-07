<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>{{ config('app.name', 'Laravel') }}</title>
</head>

<body class="v-sans">
    <div class="grid grid-cols-2 relative bg-gray-400">
        <div class="h-[63px] w-[278px] bg-contain absolute top-4 left-4"
             style="background-image: url();"></div>
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2">
            <div class="rounded-full px-6 p-3 bg-gray-200 border-2 border-gray-500 font-bold text-[#384653] text-2xl">
                7 мая 2025 09:22:12
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
                  3
                </span>
            </div>
            <div class="flex flex-col gap-y-2 w-[428px]">
                <div class="rounded-3xl bg-gray-300 border-2 border-gray-500 relative">
                    <div class="flex flex-row items-center min-h-[48px]">
                        <div class="w-[56px] h-[48px]">
                            <div
                                class="flex items-center justify-center rounded-full text-xl text-[#384653] font-bold bg-[#ec6608] w-[56px] border-2 border-[#384653] absolute -left-1 -top-1 -bottom-1">
                                3
                            </div>
                        </div>
                        <div class="grid grid-cols-2 ml-[16px] w-full font-bold text-[#384653]">
                            <span class="text-left">1206/1068</span>
                            <span class="text-left">30 минут назад</span>
                        </div>
                    </div>
                </div>
                <div class="rounded-3xl bg-gray-300 border-2 border-gray-500 relative">
                    <div class="flex flex-row items-center min-h-[48px]">
                        <div class="w-[56px] h-[48px]">
                            <div
                                class="flex items-center justify-center rounded-full text-xl text-[#384653] font-bold bg-[#ec6608] w-[56px] border-2 border-[#384653] absolute -left-1 -top-1 -bottom-1">
                                2
                            </div>
                        </div>
                        <div class="grid grid-cols-2 ml-[16px] w-full font-bold text-[#384653]">
                            <span class="text-left">1246/1067</span>
                            <span class="text-left">1 час назад</span>
                        </div>
                    </div>
                </div>
                <div class="rounded-3xl bg-gray-300 border-2 border-gray-500 relative">
                    <div class="flex flex-row items-center min-h-[48px]">
                        <div class="w-[56px] h-[48px]">
                            <div
                                class="flex items-center justify-center rounded-full text-xl text-[#384653] font-bold bg-[#ec6608] w-[56px] border-2 border-[#384653] absolute -left-1 -top-1 -bottom-1">
                                1
                            </div>
                        </div>
                        <div class="grid grid-cols-2 ml-[16px] w-full font-bold text-[#384653]">
                            <span class="text-left">1148/1066</span>
                            <span class="text-left">5 часов назад</span>
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
                  3
                </span>
            </div>
            <div class="flex flex-col gap-y-2 w-[428px]">
                <div class="rounded-3xl bg-gray-300 border-2 border-gray-500 relative">
                    <div class="flex flex-row items-center min-h-[48px]">
                        <div class="w-[56px] h-[48px]">
                            <div
                                class="flex items-center justify-center text-xl rounded-full text-[#384653] font-bold bg-[#ec6608] w-[56px] border-2 border-[#384653] absolute -left-1 -top-1 -bottom-1">
                                3
                            </div>
                        </div>
                        <div class="grid grid-cols-2 ml-[16px] w-full font-bold text-[#384653]">
                            <span class="text-left">Вера М</span>
                            <span class="text-left">8 минут назад</span>
                        </div>
                    </div>
                </div>
                <div class="rounded-3xl bg-gray-300 border-2 border-gray-500 relative">
                    <div class="flex flex-row items-center min-h-[48px]">
                        <div class="w-[56px] h-[48px]">
                            <div
                                class="flex items-center justify-center text-xl rounded-full text-[#384653] font-bold bg-[#ec6608] w-[56px] border-2 border-[#384653] absolute -left-1 -top-1 -bottom-1">
                                2
                            </div>
                        </div>
                        <div class="grid grid-cols-2 ml-[16px] w-full font-bold text-[#384653]">
                            <span class="text-left">Вера А</span>
                            <span class="text-left">10 минут назад</span>
                        </div>
                    </div>
                </div>
                <div class="rounded-3xl bg-gray-300 border-2 border-gray-500 relative">
                    <div class="flex flex-row items-center min-h-[48px]">
                        <div class="w-[56px] h-[48px]">
                            <div
                                class="flex items-center justify-center text-xl rounded-full text-[#384653] font-bold bg-[#ec6608] w-[56px] border-2 border-[#384653] absolute -left-1 -top-1 -bottom-1">
                                1
                            </div>
                        </div>
                        <div class="grid grid-cols-2 ml-[16px] w-full font-bold text-[#384653]">
                            <span class="text-left">Вероника Ы</span>
                            <span class="text-left">11 минут назад</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>
