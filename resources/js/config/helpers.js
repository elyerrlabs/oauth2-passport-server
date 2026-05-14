
export function errors(errors) {
    const formatted = {};

    Object.entries(errors).forEach(([key, value]) => {

        const cleanMessage = value
            .replace(/\.\d+\./g, ' ')
            .replace(/\.\d+/g, ' ')
            .replace(/\s+/g, ' ')
            .trim();

        if (key.includes('.')) {
            const keys = key.split('.');
            let current = formatted;

            keys.forEach((k, index) => {
                if (index === keys.length - 1) {
                    current[k] = cleanMessage;
                } else {
                    if (!current[k]) {
                        const nextKey = keys[index + 1];
                        current[k] = /^\d+$/.test(nextKey) ? [] : {};
                    }
                    current = current[k];
                }
            });
        } else {
            formatted[key] = cleanMessage;
        }
    });

    return formatted;
}

