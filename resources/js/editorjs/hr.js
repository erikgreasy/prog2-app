export default class {
    static get toolbox() {
        return {
            title: 'Hr',
            icon: '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" /></svg>'
        };
    }

    render() {
        const el = document.createElement('hr');

        el.style.margin = '2rem 0';

        return el;
    }

    save(blockContent) {
        return {
        }
    }

}