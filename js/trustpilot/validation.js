Validation.add(
  'validate-trustpilot-script',
  'You have pasted in a wrong script, please visit Trustpilot Business site to find a correct script',
  function (value) {
    try {
      const parser = new DOMParser();
      var doc = parser.parseFromString(value, 'text/xml');
      if (doc.getElementsByTagNameNS('*', 'parsererror').length > 0) {
        return false;
      }
      return true;
    } catch (err) {
      return false;
    }
  }
);

Validation.add(
  'validate-trustpilot-key',
  'You have pasted in a wrong key, please visit Trustpilot Business site to find a correct key',
  function (value) {
    if (value.trim().length === 16) {
      return true;
    }
    return false;
  }
);