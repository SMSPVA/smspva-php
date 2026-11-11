# PHP method index

Each method takes a parameter array and an optional options array. PHPDoc in `src/` contains input shapes; `resources/contracts.json` preserves wire schemas.

## activation

| Method | HTTP | Parameters |
| --- | --- | --- |
| `$client->activation->getBalance` | `GET /activation/balance` | None |
| `$client->activation->getUserInfo` | `GET /activation/userinfo` | None |
| `$client->activation->getOperators` | `GET /activation/operators/{country}` | `country` (required) |
| `$client->activation->getConversions` | `GET /activation/conversions/{service}` | `service` (required) |
| `$client->activation->getMessageTypeSettings` | `GET /activation/countryservicesettings` | None |
| `$client->activation->getAllNumberCounts` | `GET /activation/countnumbers` | None |
| `$client->activation->getCountryNumberCounts` | `GET /activation/countnumbers/{country}` | `country` (required) |
| `$client->activation->getAllNumberCountsCompact` | `GET /activation/countnumberscompact` | None |
| `$client->activation->getCountryNumberCountsCompact` | `GET /activation/countnumberscompact/{country}` | `country` (required) |
| `$client->activation->getServicePrice` | `GET /activation/serviceprice/{country}/{service}` | `country` (required), `service` (required), `voice` |
| `$client->activation->getCountryOperatorPrices` | `GET /activation/serviceprice/{country}` | `country` (required), `voice` |
| `$client->activation->getServicePrices` | `GET /activation/serviceprices/{service}` | `service` (required), `voice` |
| `$client->activation->getAllServicePrices` | `GET /activation/servicesprices` | `voice` |
| `$client->activation->getNumber` | `GET /activation/number/{country}/{service}` | `country` (required), `service` (required), `voice` |
| `$client->activation->getNumberByOperator` | `GET /activation/number/{country}/{service}/{operator}` | `country` (required), `service` (required), `operator` (required), `voice` |
| `$client->activation->repeatNumber` | `POST /activation/repeatnumber/{number}/{service}` | `number` (required), `service` (required) |
| `$client->activation->getPhoneStatus` | `GET /activation/statusphone/{orderid}` | `orderid` (required) |
| `$client->activation->startSms` | `PUT /activation/startsms/{orderid}` | `orderid` (required) |
| `$client->activation->receiveSms` | `GET /activation/sms/{orderid}` | `partnerkey`, `orderid` (required) |
| `$client->activation->getOrders` | `GET /activation/orders` | None |
| `$client->activation->getNumberStatus` | `GET /activation/numberstatus/{number}/{service}` | `number` (required), `service` (required) |
| `$client->activation->clearSms` | `PUT /activation/clearsms/{orderid}` | `orderid` (required) |
| `$client->activation->stopSms` | `PUT /activation/stopsms/{orderid}` | `orderid` (required) |
| `$client->activation->cancelOrder` | `PUT /activation/cancelorder/{orderid}` | `orderid` (required) |
| `$client->activation->blockNumber` | `PUT /activation/blocknumber/{orderid}` | `orderid` (required) |

## rental

| Method | HTTP | Parameters |
| --- | --- | --- |
| `$client->rental->getCountries` | `GET /api/rent.php?method=getcountries` | None |
| `$client->rental->getDefaultServices` | `GET /api/rent.php?method=get_default_services` | None |
| `$client->rental->getCountriesByService` | `GET /api/rent.php?method=get_country_by_service` | `service` (required), `dtype` (required), `dcount` |
| `$client->rental->getData` | `GET /api/rent.php?method=getdata` | `country` (required), `dtype`, `dcount`, `extend` |
| `$client->rental->getDataWithProviders` | `GET /api/rent.php?method=getdataWithProviders` | `country` (required), `dtype`, `dcount`, `extend` |
| `$client->rental->getCount` | `GET /api/rent.php?method=getcount` | `country` (required), `dtype`, `dcount` |
| `$client->rental->getCountWithProviders` | `GET /api/rent.php?method=getCountWithProviders` | `country` (required), `dtype`, `dcount` |
| `$client->rental->getMultiServiceCount` | `GET /api/rent.php?method=get_count_multi` | `country` (required), `services` (required), `dtype` (required), `dcount` (required) |
| `$client->rental->getCanadaPaypalDays` | `GET /api/rent.php?method=get_days_canada_paypal` | None |
| `$client->rental->getMinimumPaypalDays` | `GET /api/rent.php?method=get_min_paypal_days` | `country` (required), `service` (required) |
| `$client->rental->create` | `GET /api/rent.php?method=create` | `dtype` (required), `dcount` (required), `country` (required), `service` (required), `provider`, `partner_key` |
| `$client->rental->createMulti` | `GET /api/rent.php?method=create_multi` | `dtype` (required), `dcount` (required), `country` (required), `services` (required), `provider`, `partner_key` |
| `$client->rental->addServiceToOrder` | `GET /api/rent.php?method=add_service_to_order` | `id` (required), `service` (required), `pnumber` (required), `rent_days` |
| `$client->rental->getOrders` | `GET /api/rent.php?method=orders` | None |
| `$client->rental->activate` | `GET /api/rent.php?method=activate` | `id` (required) |
| `$client->rental->getSms` | `GET /api/rent.php?method=sms` | `id` (required) |
| `$client->rental->prolong` | `GET /api/rent.php?method=prolong` | `id` (required), `dtype` (required), `dcount` (required) |
| `$client->rental->prolongByDays` | `GET /api/rent.php?method=prolong_max` | `id` (required), `dtype` (required), `dcount` (required) |
| `$client->rental->deleteOrder` | `GET /api/rent.php?method=delete` | `id` (required) |
| `$client->rental->getHistory` | `GET /api/rent.php?method=get_rent_history` | `skip`, `take` |
| `$client->rental->getRestoreCost` | `GET /api/rent.php?method=restore_user_precalc` | `id` (required) |
| `$client->rental->restore` | `GET /api/rent.php?method=restore_user` | `id` (required) |

## email

| Method | HTTP | Parameters |
| --- | --- | --- |
| `$client->email->getProviders` | `GET /api/rent_email.php?method=get_providers` | None |
| `$client->email->getData` | `GET /api/rent_email.php?method=get_data` | `pcode` |
| `$client->email->getCount` | `GET /api/rent_email.php?method=get_count` | `pcode` |
| `$client->email->createOrder` | `GET /api/rent_email.php?method=create_order` | `scode` (required), `pcode`, `dtype` (required), `dcount` (required) |
| `$client->email->getOrders` | `GET /api/rent_email.php?method=get_orders` | None |
| `$client->email->getMails` | `GET /api/rent_email.php?method=get_mails` | `orderid` (required), `t`, `s`, `sq` |
| `$client->email->getMailBody` | `GET /api/rent_email.php?method=get_mail_body` | `orderid` (required), `emailid` (required) |
| `$client->email->buyAlias` | `GET /api/rent_email.php?method=buy_alias` | `orderid` (required), `aliasid` (required) |
| `$client->email->getMailsByAlias` | `GET /api/rent_email.php?method=get_mails_by_alias` | `orderid` (required), `aliasid` (required), `t`, `s`, `sq` |
| `$client->email->prolongOrder` | `GET /api/rent_email.php?method=prolong_order` | `orderid` (required), `dtype` (required), `dcount` (required) |
| `$client->email->cancelOrder` | `GET /api/rent_email.php?method=cancel_order` | `orderid` (required) |
| `$client->email->getArchivedOrders` | `GET /api/rent_email.php?method=get_archived_orders` | None |
| `$client->email->calcRestoreOrder` | `GET /api/rent_email.php?method=calc_restore_order` | `orderid` (required) |
| `$client->email->restoreOrder` | `GET /api/rent_email.php?method=restore_order` | `orderid` (required) |

Activation also provides `waitForSms($orderid, $expiresAt, $interval = 5.0, $partnerkey = null)`, where expiry is Unix seconds.
