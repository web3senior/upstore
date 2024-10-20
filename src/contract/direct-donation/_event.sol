// SPDX-License-Identifier: MIT
pragma solidity ^0.8.24;

event Log(string func, uint256 gas);
event feeUpdated(uint8 fee);
event TokenAdded(bytes32 indexed id, address  addr, address sender);
event TokenUpdated(bytes32 indexed id, address addr, address sender);
event TokenRemoved(bytes32 indexed _tokenId, address sender);
event Donated(address _from, address _to, uint256 _amount, bytes32 indexed _tokenId, bytes32 indexed _appId);