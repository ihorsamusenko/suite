const commandLineParser = require('commander');
const { join } = require('path');
const { globalSettings } = require('../settings');

const collectArguments = (argument, argumentCollection) => {
    argumentCollection.push(argument);
    return argumentCollection;
};

const getMode = requestedMode => {
    for (const mode in globalSettings.modes) {
        if (globalSettings.modes[mode] === requestedMode) {
            return requestedMode;
        }
    }
    console.warn(`Mode "${commandLineParser.mode}" is not available`);
    process.exit(1);
};

let mode;
commandLineParser
    .option('-n, --namespace <namespace name>', 'build the requested namespace. Multiple arguments are allowed.', collectArguments, [])
    .option('-t, --theme <theme name>', 'build the requested theme. Multiple arguments are allowed.', collectArguments, [])
    .option('-i, --info', 'information about all namespaces and available themes')
    .option('-c, --config <path>', 'path to JSON file with namespace config', globalSettings.paths.namespaceConfig)
    .arguments('<mode>')
    .action(function (modeValue) {
        const modeIndexInArgs = process.argv.findIndex(element => element === modeValue);
        console.log(modeIndexInArgs);

        // const { remain: args } = JSON.parse(process.env.npm_config_argv);
        // const passedOptions = args.filter(arg => !arg.indexOf('-'));
        // const allowedOptions = this.options.reduce((options, currentOption) => {
        //     const { short } = currentOption;
        //     const { long } = currentOption;
        //
        //     if ( short ) {
        //         options.push(short);
        //     };
        //
        //     if ( long ) {
        //         options.push(long);
        //     };
        //
        //     return options;
        // }, []);
        //
        // passedOptions.forEach(opt => {
        //     if (!allowedOptions.includes(opt)) {
        //         //throw new Error(`option ${opt} is not allowed`);
        //     }
        //});

        mode = getMode(modeValue);
    })
    .parse(process.argv);

const namespaces = commandLineParser.namespace;
const themes = commandLineParser.theme;
const pathToConfig = join(globalSettings.context, commandLineParser.config);

const namespaceJson = require(pathToConfig);
if (commandLineParser.info === true) {
    console.log('Namespaces with available themes:');
    namespaceJson.namespaces.forEach(namespaceConfig => {
        console.log(`- ${namespaceConfig.namespace}`);
        console.log(`  ${namespaceConfig.defaultTheme}`);
        if (namespaceConfig.themes.length) {
            namespaceConfig.themes.forEach(theme => console.log(`  ${theme}`));
        }
    });
    console.log('');
    process.exit();
}

module.exports = {
    mode,
    namespaces,
    themes,
    pathToConfig
};
