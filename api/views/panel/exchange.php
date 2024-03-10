<?php
// Which resource will expire in next 5 days
$fiveDaysFromNow = date("Y-m-d", time() + (86400 * 5));
?>
<section>
    <div class="__frame" data-width="xxlarge">
        <?php
        if (isset($_GET['insert'])) {
            $insert = $_GET['insert'];
            if ($insert == 1)
                echo '<p class="alert alert-success">Record has been added. ID=>' . $_GET['exchange_id'] . '</p>';
            else
                echo '<p class="alert alert-danger">Err: ' . $_GET['msg'] . '</p>';
        }
        ?>
        <div class="ms-Grid-row">
            <div class="ms-Grid-col ms-sm12">
                <div class="card">
                    <div class="card-header">
                        <ul class="d-flex justify-content-between align-items-center">
                            <li> <?= $this->title ?></li>
                            <li>
                                <a href="javascript: " onclick="location.replace(location.pathname)">
                                    <i class="ms-Icon ms-Icon--Refresh" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <form>
                            <ul class="d-flex flex-row justify-content-start">
                                <li>
                                    <input type="number" name="exchange_id" placeholder="Exchange ID" value="<?= (isset($_GET['exchange_id']) ? $_GET['exchange_id'] : null) ?>">
                                </li>
                                <li class="ml-10">
                                    <input type="submit" value="Search">
                                </li>
                            </ul>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <th>Exch. ID</th>
                                    <th>operator</th>
                                    <th>client info</th>
                                    <th>reason</th>
                                    <th>rate info</th>
                                    <th>fee</th>
                                    <th>fnt 👮</th>
                                    <th>type</th>
                                    <th>at</th>
                                    <th>amount</th>
                                    <th>bank cost</th>
                                    <th>total cost</th>
                                    <th>profit</th>
                                    <th>Total Customer Pay</th>
                                    <th>status</th>
                                    <th>Actions</th>
                                </thead>
                                <?php
                                foreach ($this->data['data'] as $key => $value) {
                                    $id = $value['exchange_id'];
                                ?>
                                    <tr>
                                        <td>#<?= $id ?></td>
                                        <td><?= $value['fullname'] ?></td>
                                        <td>
                                            <span class="badge badge-primary"> # <?= $value['client_id'] ?></span>
                                            <span class="badge badge-secondary"><?= $value['firstname'] . ' ' . $value['lastname'] ?></span>
                                            <span class="badge badge-dark"><?= $value['nationalcode'] ?></span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light"><?= $value['reason_name'] ?></span>
                                        </td>
                                        <td>
                                            Buy:
                                            <span class="text-success">
                                                <?= number_format($value['rate_buy'], 4, '.', ',') ?>$
                                            </span>
                                            <br />
                                            Sell:
                                            <span class="text-danger">
                                                <?= number_format($value['rate_sell'], 4, '.', ',') ?>$
                                            </span>
                                        </td>
                                        <td>
                                            <p class="text-primary"><span class="text-dark">BC:</span><?= $value['buy_unit_name'] ?></p>
                                            <p class="text-primary"><span class="text-dark">PC:</span><?= $value['pay_unit_name'] ?></p>
                                            <p class="text-primary"><span class="text-dark">CPT:</span><?= $value['customer_pay_type_name'] ?></p>
                                            <p class="text-primary"><span class="text-dark">CRT:</span><?= $value['customer_receive_type_name'] ?></p>
                                        </td>
                                        <td class="text-center">
                                            <?= ($value['fintrac']) ? '<span class="badge badge-danger badge-pill">Yes</span>' : '<span class="badge badge-success badge-pill">No</span>'; ?>
                                        </td>
                                        <td class="text-center">
                                            <?= ($value['type'] === 'sell') ? '<span class="badge badge-danger badge-pill">Sell</span>' : '<span class="badge badge-success badge-pill">Buy</span>'; ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-warning">
                                                <?= $value['at'] ?>
                                            </span>
                                        </td>
                                        <td><?= number_format($value['amount'], 2, '.', ',') ?></td>
                                        <td><?= number_format($value['bank_cost'], 2, '.', ',') ?></td>
                                        <td><?= number_format($value['total_cost'], 2, '.', ',') ?></td>
                                        <td><?= number_format($value['profit'], 2, '.', ',') ?></td>
                                        <td><?= number_format($value['total_customer'], 2, '.', ',') ?></td>
                                        <td><?= $value['exchange_status'] ?></td>
                                        <td>
                                            <div class="d-flex justify-content-between">
                                                <a href="javascript: " onclick="editRow(<?= $id ?>)"><?= ICON_EDIT ?></a>
                                                <a href="<?= URL ?>panel/<?= $this->endpoint ?>/delete?id=<?= $id ?>"><?= ICON_DELETE ?></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="16" class="text-center">
                                            <span class="badge badge-purpink"><?= $value['transaction_count'] ?></span>
                                            Transactions has been found
                                            |
                                            <a href="<?= URL ?>panel/transaction?id=<?= $id ?>" style="color:brown;border:1px solid #eee;background: #f8f8f8;font-weight: bold;padding:.2rem">
                                            Add/Manage Transactions
                                        </a>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ms-Grid-col ms-sm12 ms-md12">
                <?= $this->title ?> list [<?= (new Paging)->getTotal($this->data['total']); ?>-page]
                <?= (new Paging)->show(PATH_ADMIN . $this->endpoint, $this->data['total'], $this->pg); ?>
            </div>

            <form action="<?= URL ?>panel/<?= $this->endpoint ?>/insert" method="post" enctype="multipart/form-data" autocomplete="off">
                <div class="ms-Grid-col ms-sm12 ms-md9">
                    <div class="card">
                        <div class="card-header">Insert & Update</div>
                        <div class="card-body">
                            <div class="ms-Grid-row">
                                <div class="ms-Grid-col ms-sm12 ms-md12">
                                    <details open>
                                        <summary>Step1 - Customer & Reason & Amount & FNT</summary>
                                        <div>
                                            <div class="d-flex flex-row justify-content-start align-items-center">
                                                <div class="form-group flex-fill">
                                                    <label for="client_id" data-required="on">client</label>
                                                    <select class="form-control" name="client_id" required>
                                                        <option value="">--Select--</option>
                                                        <?php
                                                        foreach ($this->client as $val) {
                                                        ?>
                                                            <option value="<?= $val["client_id"] ?>"><?= $val["firstname"] . ' ' . $val["lastname"] . ' (#' . $val["client_id"] . ')' ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="reason_id" data-required="on">reason</label>
                                                    <select class="form-control" name="reason_id" required>
                                                        <option value="">--Select--</option>
                                                        <?php
                                                        foreach ($this->reason as $val) {
                                                        ?>
                                                            <option value="<?= $val["reason_id"] ?>"><?= $val["name"] ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="amount" data-required="on">amount</label>
                                                    <input type="number" class="form-control" name="amount" min="0" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="fnt" data-required="on">FNT</label>
                                                    <select class="form-control" name="fintrac" required>
                                                        <option value="0">No</option>
                                                        <option value="1">Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                    </details>

                                    <details open>
                                        <summary>
                                            Step2 - Fees (Buy Currency/ Pay Currency/ Customer Pay Type/ Customer
                                            Receive Type)
                                        </summary>
                                        <div class="d-flex justify-content-between">
                                            <div class="form-group">
                                                <label for="type" data-required="on">Type</label>
                                                <select class="form-control" name="type" required>
                                                    <option value="">--Select--</option>
                                                    <option value="sell">Sell</option>
                                                    <option value="buy">Buy</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="buy_currency" data-required="on">Buy Currency (that user
                                                    will get)</label>
                                                <select class="form-control" name="buy_currency" required>
                                                    <option value="">--Select--</option>
                                                    <?php
                                                    foreach ($this->unit as $val) {
                                                    ?>
                                                        <option value="<?= $val["unit_id"] ?>"><?= $val["name"] ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="pay_currency" data-required="on">Pay Currency (that user
                                                    will
                                                    give)</label>
                                                <select class="form-control" name="pay_currency" required>
                                                    <option value="">--Select--</option>
                                                    <?php
                                                    foreach ($this->unit as $val) {
                                                    ?>
                                                        <option value="<?= $val["unit_id"] ?>"><?= $val["name"] ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="customer_pay_Type" data-required="on">Customer Pay
                                                    Type</label>
                                                <select class="form-control" name="customer_pay_type" required>
                                                    <option value="">--Select--</option>
                                                    <?php
                                                    foreach ($this->paytype as $val) {
                                                    ?>
                                                        <option value="<?= $val["paytype_id"] ?>"><?= $val["name"] ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="customer_receive_Type" data-required="on">Customer Receive
                                                    Type</label>
                                                <select class="form-control" name="customer_receive_type" required>
                                                    <option value="">--Select--</option>
                                                    <?php
                                                    foreach ($this->paytype as $val) {
                                                    ?>
                                                        <option value="<?= $val["paytype_id"] ?>"><?= $val["name"] ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </details>
                                </div>

                                <div class="ms-Grid-col ms-sm12 ms-md12">
                                    <div class="form-group">
                                        <button type="submit">Add</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="ms-Grid-col ms-sm12 ms-md3 sticky">
                    <div class="card ">
                        <div class="card-header">Summery Of Current Deal</div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <span>Amount:</span>
                                    <span class="ms-fontWeight-bold" id="amount"></span>
                                    <span class="ms-fontWeight-bold" id="amount_unit_name"></span>
                                </li>
                                <li class="list-group-item">
                                    <span title="Type + Buy Currency => Get last rate">Base Price(From Daily Rate):</span>
                                    <span class="ms-fontWeight-bold" id="base_price"></span>
                                    <input type="hidden" name="rate_id" value="" autocomplete="off">
                                </li>
                                <li class="list-group-item">
                                    <span>Service Fee(Min/Max):</span>
                                    <span id="fee_search_result" class="text-danger"></span>
                                    <!--IF there is no any record of fee-->
                                    <span class="ms-fontWeight-bold  text-info" id="service_fee"></span>
                                    <span class="ms-fontWeight-bold  text-danger" id="min_fee"></span>
                                    <span class="ms-fontWeight-bold  text-warning" id="max_fee"></span>
                                    <input type="hidden" name="fee_id" value="" autocomplete="off">
                                </li>
                                <li class="list-group-item">
                                    <span>Fee Amount(Profit):</span>
                                    <span class="ms-fontWeight-bold" id="profit"></span>
                                    <input type="hidden" name="profit" value="" autocomplete="off">
                                </li>
                                <li class="list-group-item">
                                    <span>Bank Cost:</span>
                                    <span class="ms-fontWeight-bold text-primary" id="bank_fee"></span>
                                    <input type="hidden" name="bank_cost" value="" autocomplete="off">
                                </li>
                                <li class="list-group-item">
                                    <span>Total Cost:</span>
                                    <span class="ms-fontWeight-bold" id="total_cost"></span>
                                    <input type="hidden" name="total_cost" value="" autocomplete="off">
                                </li>
                                <li class="list-group-item">
                                    <span>Total Should Customer Pay:</span>
                                    <span class="ms-fontWeight-bold" id="total_cost_customer_should_pay"></span>
                                    <input type="hidden" name="total_customer" value="" autocomplete="off">
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
</section>

<script type="text/javascript">
    let basePrice

    const calcFeeAmount = (service_fee, min_fee, max_fee) => {
        let calcFee = (document.querySelector('input[name="amount"]').value / 100) * service_fee
        if (calcFee <= min_fee) return min_fee
        if (calcFee >= max_fee) return max_fee
    }

    const calcTotalCost = (service_fee, bank_fee, min_fee, max_fee) => {
        return (calcFeeAmount(service_fee, min_fee, max_fee) + bank_fee)
    }

    const searchFee = async () => {
        let type = document.querySelector('select[name="type"]').value
        let buy_currency = document.querySelector('select[name="buy_currency"]').value
        let pay_currency = document.querySelector('select[name="pay_currency"]').value
        let customer_pay_type = document.querySelector('select[name="customer_pay_type"]').value
        let customer_receive_type = document.querySelector('select[name="customer_receive_type"]').value

        if (buy_currency !== "" && pay_currency !== "" && customer_pay_type !== "" && customer_receive_type !== "" && type !== "") {
            loading(true)
            const data = {
                buy_currency: buy_currency,
                pay_currency: pay_currency,
                customer_pay_type: customer_pay_type,
                customer_receive_type: customer_receive_type
            }

            await fetch('<?= URL ?>panel/fee/search?data=' + JSON.stringify(data))
                .then(response => response.json())
                .then(obj => {
                    loading(false)
                    console.log(obj)
                    if (!obj.result) {
                        document.querySelector('#fee_search_result').innerHTML = obj.message
                        document.querySelector('#fee_search_result').innerHTML = ''
                        document.querySelector('#service_fee').innerText = ''
                        document.querySelector('#min_fee').innerText = ''
                        document.querySelector('#max_fee').innerText = ''
                        document.querySelector('#bank_fee').innerText = ''
                        document.querySelector('#profit').innerText = ''
                        document.querySelector('#total_cost').innerText = ''
                        document.querySelector('#total_cost_customer_should_pay').innerHTML = ''
                        return false
                    }

                    let service_fee = obj.data.service_fee
                    let min_fee = obj.data.min_fee
                    let max_fee = obj.data.max_fee
                    let bank_fee = obj.data.bank_fee

                    document.querySelector('#fee_search_result').innerHTML = ""
                    document.querySelector('input[name="fee_id"]').value = obj.data.fee_id
                    document.querySelector('#service_fee').innerText = service_fee + '%'
                    document.querySelector('#min_fee').innerText = min_fee + '$'
                    document.querySelector('#max_fee').innerText = max_fee + '$'
                    document.querySelector('#bank_fee').innerText = bank_fee + '$'
                    document.querySelector('input[name="bank_cost"]').value = bank_fee
                    document.querySelector('input[name="profit"]').value = calcFeeAmount(obj.data.service_fee, min_fee, max_fee)
                    document.querySelector('#profit').innerText = new Intl.NumberFormat().format(calcFeeAmount(obj.data.service_fee, min_fee, max_fee)) + '$'
                    document.querySelector('input[name="total_cost"]').value = calcTotalCost(obj.data.service_fee, obj.data.bank_fee, min_fee, max_fee)
                    document.querySelector('#total_cost').innerText = new Intl.NumberFormat().format(calcTotalCost(obj.data.service_fee, obj.data.bank_fee, min_fee, max_fee)) + '$'

                    calculateTotalCost(service_fee, bank_fee, min_fee, max_fee)
                })
                .finally(() => {
                    // Calculate total cost

                })
                .catch(error => console.log(error))
        }
    }

    const getLastRate = async () => {
        let type = document.querySelector('select[name="type"]').value
        let buy_currency = document.querySelector('select[name="buy_currency"]').value

        if (type !== "" && buy_currency !== "") {
            loading(true)
            const data = {
                buy_currency: buy_currency
            }
            await fetch('<?= URL ?>panel/rate/search?data=' + JSON.stringify(data))
                .then(response => response.json())
                .then(obj => {
                    loading(false)
                    console.log(obj)
                    if (!obj.result) {
                        document.querySelector('#base_price').innerHTML = obj.message
                        return false
                    }
                    document.querySelector('#base_price').innerHTML = ""

                    document.querySelector('input[name="rate_id"]').value = obj.data.rate_id
                    if (type === 'buy') {
                        document.querySelector('#base_price').innerHTML = new Intl.NumberFormat().format(obj.data.buy) + '$'
                        basePrice = obj.data.buy
                    } else {
                        document.querySelector('#base_price').innerHTML = new Intl.NumberFormat().format(obj.data.sell) + '$'
                        basePrice = obj.data.sell
                    }
                }).catch(error => console.log(error))
        }
    }

    const calculateTotalCost = async (service_fee, bank_fee, min_fee, max_fee) => {
        let amount = document.querySelector('input[name="amount"]').value
        let type = document.querySelector('select[name="type"]').value
        let buy_currency = document.querySelector('select[name="buy_currency"]').value
        let pay_currency = document.querySelector('select[name="pay_currency"]').value
        let customer_pay_type = document.querySelector('select[name="customer_pay_type"]').value
        let customer_receive_type = document.querySelector('select[name="customer_receive_type"]').value

        if (type !== "" && buy_currency !== "" && pay_currency !== "" && customer_pay_type !== "" && customer_receive_type !== "" && amount !== "") {
            // console.log(Number(amount))
            // console.log(calcTotalCost(service_fee, bank_fee, min_fee, max_fee))
            // console.log(basePrice)
                // console.log('========' +((Number(amount) + calcTotalCost(service_fee, bank_fee, min_fee, max_fee)) * basePrice))
            let calc = ((Number(amount) + calcTotalCost(service_fee, bank_fee, min_fee, max_fee)) * basePrice)
            
            document.querySelector('input[name="total_customer"]').value = calc
            document.querySelector('#total_cost_customer_should_pay').innerHTML = (new Intl.NumberFormat().format(calc).toString()) + '$'
        }
    }

    window.addEventListener('load', () => {
        document.querySelector('input[name="amount"]').addEventListener('change', (e) => {
            document.querySelector('#amount').innerText = new Intl.NumberFormat().format(e.target.value)
            searchFee()
            getLastRate()
        })

        document.querySelector('select[name="buy_currency"]').addEventListener('change', (e) => {
            document.querySelector('#amount_unit_name').innerText = e.target.options[e.target.selectedIndex].text
        })

        // Fetch fee information & calculate the fees
        document.querySelector('select[name="buy_currency"]').addEventListener('change', (e) => {
            searchFee()
            getLastRate()
        })

        document.querySelector('select[name="pay_currency"]').addEventListener('change', (e) => {
            searchFee()
        })
        document.querySelector('select[name="customer_pay_type"]').addEventListener('change', (e) => {
            searchFee()
        })
        document.querySelector('select[name="customer_receive_type"]').addEventListener('change', (e) => {
            searchFee()
        })

        // Fetch latest rate
        document.querySelector('select[name="type"]').addEventListener('change', () => {
            searchFee()
            getLastRate()
        })
    })

    const loading = (state = false) => {
        document.querySelector("#loading").style.opacity = state ? "1" : "0"
        document.querySelector("#loading").style.visibility = state ? "visible" : "hidden"
    }

    const editRow = async (id) => {
        loading(true)
        const requestOptions = {
            method: 'GET',
            redirect: 'follow'
        }
        return await fetch('<?= URL ?>panel/<?= $this->endpoint ?>/fetch/' + id, requestOptions)
            .then(response => response.json())
            .then(result => {
                console.log(result)
                loading(false)
                document.querySelectorAll('form')[1].action = `<?= URL ?>panel/<?= $this->endpoint ?>/update/${id}`
                document.querySelector('button[type="submit"]').innerText = "Update"
                document.querySelector('select[name="type"]').value = result.type
                document.querySelector('select[name="buy_currency"]').value = result.buy_unit
                document.querySelector('select[name="pay_currency"]').value = result.pay_unit
                document.querySelector('select[name="customer_pay_type"]').value = result.customer_pay_type
                document.querySelector('select[name="customer_receive_type"]').value = result.customer_receive_type
                document.querySelector('select[name="client_id"]').value = result.client_id
                document.querySelector('select[name="reason_id"]').value = result.reason_id
                document.querySelector('input[name="amount"]').value = result.amount
                document.querySelector('select[name="fintrac"]').value = result.fintrac
                document.querySelector('input[name="total_customer"]').value = result.total_customer
                document.querySelector('input[name="total_cost"]').value = result.total_cost
                document.querySelector('input[name="bank_cost"]').value = result.bank_cost
                document.querySelector('input[name="rate_id"]').value = result.rate_id
                document.querySelector('input[name="profit"]').value = result.profit
                document.querySelector('input[name="fee_id"]').value = result.fee_id

                console.log("Done - 1")
            })
            .then(() => {
                console.log("Done - 2")
                document.querySelector('#amount').innerText = new Intl.NumberFormat().format(document.querySelector('input[name="amount"]').value)
                getLastRate()
                searchFee()
            })
            .finally(() => {
                console.log("Done - 3")
                // calculateTotalCost()
            })
            .catch(error => error)
    }
</script>