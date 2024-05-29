---
Title: BIF Management
Description: Boilerplate main page.
Hidden: true
Social:
    github:
      - title: "GitHub"
        url: "https://github.com"
        icon: "github"
---
<table class="page">
    <thead>
        <tr>
            <th colspan="2">Tenant setup</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="2">
                <label for="tenant-id">Tenant</label>
                <input id="tenant-id" type="text">
                <button onClick="return getTenant();">Search</button> 
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="cyclr-cluster">Cluster</label>
                <select id="cyclr-cluster">
                    <option value="eu" selected>EU</option>
                    <option value="us">US</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="half">
                <table id="tenant-info" class="form hidden">
                    <thead>
                        <tr>
                            <th colspan="2">Tenant Information</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <label for="tenant-name">Name</label>
                            </td>
                            <td>
                                <input id="tenant-name" type="text" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="tenant-has-api">API?</label>
                            </td>
                            <td>
                                <input id="tenant-has-api" type="checkbox" disabled>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td class="half">
                <table id="account-info" class="form hidden">
                    <thead>
                        <tr>
                            <th colspan="2">Account Information</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <label for="account-id">ID</label>
                            </td>
                            <td>
                                <input id="account-id" type="text" disabled>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="account-name">Name</label>
                            </td>
                            <td>
                                <input id="account-name" type="text" disabled>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>