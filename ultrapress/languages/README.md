# Translation Files

This directory contains translation files for the Lexi AI ChatBot plugin.

## Available Translations

- Arabic (ar) - `lexi-chatbot-ar.po`

## Generating .mo Files

To generate the .mo files from the .po files, you can use one of these methods:

### Method 1: Using Poedit

1. Download and install [Poedit](https://poedit.net/)
2. Open the .po file in Poedit
3. Click "Save" - this will automatically generate the .mo file

### Method 2: Using msgfmt (Command Line)

If you have gettext installed, you can use the msgfmt command:

```bash
msgfmt lexi-chatbot-ar.po -o lexi-chatbot-ar.mo
```

## Adding New Translations

1. Copy the `lexi-chatbot-ar.po` file
2. Rename it to match your language code (e.g., `lexi-chatbot-fr.po` for French)
3. Edit the file with Poedit or a text editor
4. Generate the .mo file using one of the methods above

## Language Codes

Use the appropriate language code when naming your translation files. Some common codes:

- Arabic: ar
- English: en
- French: fr
- Spanish: es
- German: de

## Notes

- Always keep both .po and .mo files in this directory
- The plugin will automatically load the appropriate translation based on the WordPress language setting
- Make sure the text domain in the translation files matches the one defined in the plugin (lexi-ai-chatbot)
