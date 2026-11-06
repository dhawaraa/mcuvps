{{-- VPSMCU Accessibility Font Size Resizer Pill --}}
<div class="flex items-center bg-[#D8D2C2] p-1 rounded-full text-xs font-bold font-mono select-none" role="group" aria-label="{{ __('portal.font_size_label') }}">
    <button type="button" 
            onclick="setFontSize('small')" 
            data-font-size-btn="small"
            title="{{ __('portal.font_size_small') }}"
            aria-label="{{ __('portal.font_size_small') }}"
            class="px-2 py-0.5 rounded-full transition text-[#5A544A] hover:text-[#2C3E2D] flex items-center justify-center font-bold text-[11px] leading-tight">
        A<span class="text-[10px] -ml-0.5 font-sans">-</span>
    </button>
    <button type="button" 
            onclick="setFontSize('normal')" 
            data-font-size-btn="normal"
            title="{{ __('portal.font_size_normal') }}"
            aria-label="{{ __('portal.font_size_normal') }}"
            class="px-2.5 py-0.5 rounded-full transition bg-[#5A6B47] text-white shadow-xs flex items-center justify-center font-bold text-xs leading-tight">
        A
    </button>
    <button type="button" 
            onclick="setFontSize('large')" 
            data-font-size-btn="large"
            title="{{ __('portal.font_size_large') }}"
            aria-label="{{ __('portal.font_size_large') }}"
            class="px-2 py-0.5 rounded-full transition text-[#5A544A] hover:text-[#2C3E2D] flex items-center justify-center font-bold text-sm leading-tight">
        A<span class="text-[11px] -ml-0.5 font-sans">+</span>
    </button>
</div>
