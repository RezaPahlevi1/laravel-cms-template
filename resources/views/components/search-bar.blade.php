<div
    x-data="searchBar()"
    class="relative"
    @keydown.escape.window="query = ''; results = []; $refs.input.blur()"
>
    <div class="flex items-center gap-2">
        <div class="relative flex-1">
            <input
                x-ref="input"
                x-model.debounce.400ms="query"
                @input="search()"
                type="text"
                placeholder="Find pages or articles..."
                class="w-full pl-10 pr-4 py-2 text-sm bg-surface border border-border
                       rounded-lg text-text-base placeholder-text-light
                       focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary
                       transition-colors"
                autocomplete="off"
            >
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-text-light"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <a
            x-show="query.length >= 2"
            :href="`/search?q=${encodeURIComponent(query)}`"
            class="shrink-0 px-3 py-2 text-sm font-medium text-white bg-primary
                   hover:bg-primary-light rounded-lg transition-colors"
            style="display: none;"
        >
            See all
        </a>
    </div>

    {{-- Dropdown hasil pencarian instant --}}
    <div
        x-show="results.length > 0 || (loading && query.length >= 2)"
        class="absolute top-full left-0 right-0 mt-1 bg-white rounded-lg shadow-lg
               border border-border overflow-hidden z-50"
        style="display: none;"
    >
        {{-- Loading state --}}
        <div x-show="loading" class="px-4 py-3 text-sm text-text-muted">
            Searching...
        </div>

        {{-- Results --}}
        <ul x-show="!loading">
            <template x-for="result in results" :key="result.id + result.type">
                <li>
                    <a
                        :href="result.url"
                        class="flex items-start gap-3 px-4 py-3 hover:bg-surface transition-colors"
                    >
                        {{-- Type badge --}}
                        <span
                            :class="result.type === 'page'
                                ? 'bg-primary/10 text-primary'
                                : 'bg-secondary/10 text-secondary'"
                            class="shrink-0 mt-0.5 px-2 py-0.5 text-xs font-medium rounded"
                            x-text="result.type === 'page' ? 'Halaman' : 'Blog'"
                        ></span>
                        <div>
                            <p class="text-sm font-medium text-text-base" x-text="result.title"></p>
                            <p x-show="result.excerpt"
                               class="text-xs text-text-muted mt-0.5 line-clamp-1"
                               x-text="result.excerpt"></p>
                        </div>
                    </a>
                </li>
            </template>
        </ul>

        {{-- No results --}}
        <div
            x-show="!loading && results.length === 0 && query.length >= 2"
            class="px-4 py-3 text-sm text-text-muted"
        >
            No results for... "<span x-text="query"></span>"
        </div>
    </div>
</div>

<script>
function searchBar() {
    return {
        query: '',
        results: [],
        loading: false,
        controller: null,

        async search() {
            if (this.query.length < 2) {
                this.results = [];
                return;
            }

            // Batalkan request sebelumnya jika ada
            if (this.controller) this.controller.abort();
            this.controller = new AbortController();
            this.loading = true;

            try {
                const res = await fetch(
                    `/search/suggest?q=${encodeURIComponent(this.query)}`,
                    { signal: this.controller.signal }
                );
                const data = await res.json();
                this.results = data.results ?? [];
            } catch (e) {
                if (e.name !== 'AbortError') this.results = [];
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>