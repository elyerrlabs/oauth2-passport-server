/**
 * Copyright (c) 2025 Elvis Yerel Roman Concha
 *
 * This file is part of an open source project licensed under the
 * "NON-COMMERCIAL USE LICENSE - OPEN SOURCE PROJECT" (Effective Date: 2025-08-03).
 *
 * You may use, study, modify, and redistribute this file for personal,
 * educational, or non-commercial research purposes only.
 *
 * Commercial use is strictly prohibited without prior written consent
 * from the author.
 *
 * Combining this software with any project licensed for commercial use
 * (such as AGPL) is not permitted without explicit authorization.
 *
 * This software supports OAuth 2.0 and OpenID Connect.
 *
 * Author Contact: yerel9212@yahoo.es
 * 
 * 
 */
function importComponents() {
    const requireComponent = require.context(
        "../components", //directoy
        false, //includ subdirectories
        /V[A-Z]\w+\.(vue|js)$/ //expresion to get files name
    );

    const components = requireComponent.keys().map((fileName) => {         
        // get file name in PascalCase
        const componentName = fileName.replace(/^\.\/(.*)\.\w+$/, "$1");

        // import component
        const componentConfig = requireComponent(fileName);

        return [componentName, componentConfig.default || componentConfig];
    });
    
    return components;
}

export const customComponents = importComponents();
